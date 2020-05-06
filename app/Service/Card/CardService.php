<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/10
 * Time: 上午9:41
 */

namespace App\Service\Card;


use App\Exceptions\CardException;
use App\Exceptions\MemberException;
use App\Exceptions\WechatException;
use App\Http\Codes\Code;
use App\PayConfig;
use App\Service\Pay\PayCode;
use Libs\Arr;
use Libs\Filter;
use Libs\Log;
use App\Models\CardCodeModel;
use App\Models\CardModel;
use App\DataTypes\CardTypes;
use App\Models\ExchangeModel;
use App\DataTypes\ExchangeTypes;
use App\Models\MchCardsModel;
use App\Service\Wechat\Card;
use Illuminate\Support\Collection;

class CardService
{

    public static function grantLog($cardId, $wxCardId, $number)
    {
        $cardModel = new CardModel();

        if ($cardId) {
            $cardRow = $cardModel->find($cardId);
        } else {
            $cardRow = $cardModel->where('card_id', $wxCardId)->first();
        }

        $log         = compact('wxCardId', 'cardId', 'number');
        $log['file'] = __FILE__;

        if (!$cardRow) {
            Log::warning("发放数量卡券不存在", $log);
        }

        $grantQuantity           = abs($number);
        $cardRow->grant_quantity += $grantQuantity;

        if (!$grantQuantity) {
            Log::warning("发放数量更新失败", $log);
        }

        $cardRow->save();
    }

    public function getSaveFieldsData($reqeust_data)
    {
        $definedFields = self::getNeedSaveFields();
        $pavedData     = Arr::paved($reqeust_data);

        $saveData = [];
        foreach ($definedFields as $field => $name) {
            if (isset($pavedData[$name])) {
                $saveData[$field] = array_get($pavedData, $name);
            }
        }

        $saveData['background_pic_url'] = array_get($saveData, 'background_pic_url') ?: array_get($reqeust_data, 'background_pic_url');

        return $saveData;
    }

    public function parse(Collection &$request, $cardType)
    {
        $wechatData = $request->get('wechat');
        $cardType   = strtoupper($cardType);

        $pavedDefined      = Arr::paved(CardTypes::getDefindFields($cardType));
        $pavedWechatData   = Arr::paved($wechatData);
        $completePavedData = array_merge($pavedDefined, $pavedWechatData);
        return Arr::unPaved($completePavedData);
    }

    public function updateParse(Collection &$request, $cardType)
    {
        $wechatData = $request->get('wechat', []);

        return $wechatData;

        $pavedDefined    = CardTypes::getCanEditFields($cardType);
        $pavedWechatData = Arr::paved($wechatData);

        $result = [];

        foreach ($pavedDefined as $canEditField) {
            if (isset($pavedWechatData[$canEditField])) {
                $result[$canEditField] = $pavedWechatData[$canEditField];
            }
        }
        return Arr::unPaved($result);
    }


    public static function getNeedSaveFields()
    {
        return [
            'title'              => 'wechat.base_info.title',
            'total_quantity'     => 'wechat.base_info.sku.quantity',
            'quantity'           => 'wechat.base_info.sku.quantity',
            'logo_url'           => 'wechat.base_info.logo_url',
            'not_overdue'        => 'wechat.base_info.date_info.type',
            'begin_time'         => 'wechat.base_info.date_info.begin_timestamp',
            'end_time'           => 'wechat.base_info.date_info.end_timestamp',
            'least_cost'         => 'wechat.least_cost',
            'reduce_cost'        => 'wechat.reduce_cost',
            'discount'           => 'wechat.discount',
            'type'               => 'card_type',
            'can_overlay'        => 'can_overlay',
            'can_exchange'       => 'can_exchange',
            'exchange_value'     => 'exchange_value',
            'exchange_name'      => 'exchange_name',
            'get_limit'          => 'wechat.base_info.get_limit',
            'background_pic_url' => 'wechat.background_pic_url',
            'color'              => 'wechat.base_info.color',
        ];
    }

    public function checkExchangeData(Collection $exchange_data)
    {
        if (!$exchange_data->get('can_exchange')) {
            return;
        }

        ExchangeTypes::checkType($exchange_data->get('exchange_name'));

        if (!$exchange_data->get('exchange_value')) {
            throw new CardException("兑换额度错误");
        }
    }

    public function save($data)
    {
        $model = new CardModel();

        $data['type']   = strtoupper($data['type']);
        $data['mch_id'] = $this->getFirstMchId($data);

        $model->fill($data);

        $model->save();

        $this->saveExchange($model->id, 0, $data);

        $this->saveMchs($model->id, $data);

        return $model->id;
    }

    public function getFirstMchId($data)
    {
        $mch_ids = array_get($data, 'mch_ids', []);

        if (!$mch_ids) return 0;

        return array_shift($mch_ids);
    }

    public function saveMchs($card_id, $data)
    {
        $mch_ids         = array_get($data, 'mch_ids', []);
        $mch_cards_model = new MchCardsModel();

        $mch_cards_model->where('card_id', $card_id)->delete();

        foreach ($mch_ids as $mch_id) {
            $mch_cards_model->create([
                'mch_id'  => (int)$mch_id,
                'card_id' => $card_id
            ]);
        }
    }

    public function saveExchange($card_id, $old_can_exchange, $data)
    {
        $exchange_data  = new Collection($data);
        $exchange_model = new ExchangeModel();

        if ($exchange_data->get('can_exchange')) {
            $data['card_id'] = $card_id;

            $exchange_model->create($data);
        } else {
            if ($old_can_exchange) {
                $exchange_model->where('card_id', $card_id)->delete();
            }
        }
    }

    public function updateSave(CardModel $model, $data)
    {
        $saveData         = [];
        $old_can_exchange = $model->can_exchange;

        foreach ($data as $k => $v) {
            if ($k == 'type') continue;

            $saveData[$k] = $v;
        }

        $saveData['mch_id'] = $this->getFirstMchId($data);

        $model->fill($saveData);

        $this->saveExchange($model->id, $old_can_exchange, $data);

        $this->saveMchs($model->id, $data);

        return $model->save();
    }

    public function getCardByCodeId($code_id)
    {
        $code_model = new CardCodeModel();

        $card_id = $code_model->where('id', $code_id)->value('card_id');

        $card_model = new CardModel();

        $card_row = $card_model->find($card_id);

        if (!$card_row) {
            throw new MemberException("会员卡不存在", Code::card_not_exists, compact('card_id', 'code_id'));
        }

        return $card_row;
    }

    public function getCardIdByCode($list)
    {
        $result            = [];
        $wechatCardService = new Card();
        $cardModel         = new CardModel();

        foreach ($list as $code) {

            if (!$code) {
                $result[] = 0;
                continue;
            }

            $wechatCardService->getCardByCode($code);

            $wxCardId = $wechatCardService->result()->getData()->get('card')['card_id'];

            $card = $cardModel->where('card_id', $wxCardId)->first();

            $result[] = $card->id;
        }

        return $result;
    }

}