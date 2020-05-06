<?php namespace App\Http\Controllers\Pay;


use App\Exceptions\CardException;
use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\WechatCardRule;
use Libs\Response;
use App\Models\CardCodeModel;
use App\Models\CardModel;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use App\DataTypes\OutStrTypes;
use App\Models\PayCardConsumeLog;
use App\Models\PayOrderDetailModel;
use App\Service\Card\CodeService;
use App\Service\Card\States\CardActor;
use App\Service\Mch\Mch;
use App\Service\Wechat\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CardController extends BaseController {


    public function rule()
    {
        return new WechatCardRule();
    }

    public function getWxCardInfo(ApiVerifyRequest $request)
    {
        $code = $request->get('code');
        $card_model    = new CardModel();
        $wx_card_service = new Card();
        $mch_name      = '';

        $wx_card_data = $wx_card_service->getCardByCode($code);

        $wx_card_id = $wx_card_data->get('card')['card_id'];

        $card_row = $card_model->where('card_id', $wx_card_id)->first();

        if($card_row->type == CardTypes::member_card){
            throw new CardException("这是会员卡");
        }

        if(!$card_row){
            throw new CardException("卡券不存在：" . $code);
        }

        $card_code_model = new CardCodeModel();

        $code_row = $card_code_model->where('card_id', $card_row->id)->where('code_no', $code)->first();

        if(!$code_row){
            throw new CardException("没有找到领取记录");
        }

        if($card_row->mch_id){
            $mch_model = new Mch();
            $mch_row   = $mch_model->getInfo($card_row->mch_id);
            $mch_name  = $mch_row->name;
        }

        $wx_card = $wx_card_service->get($card_row->card_id);

        return Response::success('', [
            'card_code' => $code,
            'card_id'   => $card_row->id,
            'card_type' => $card_row->type,
            'mch_id'    => $card_row->mch_id,
            'member_id' => $code_row->member_id,
            'status'    => CardStatus::codeStatus($card_row, $code_row),
            'begin_time' => $code_row->start_time,
            'end_time' => $code_row->end_time,
            'mch_name' => (string)$mch_name,
            'logo_url'     => $card_row->logo_url,
            'title'    => $card_row->title,
            'notice'        => $wx_card['base_info']['notice'],
            'description'   => $wx_card['base_info']['description'],
        ]);
    }

    public function getMemberCard(ApiVerifyRequest $request)
    {
        $code = $request->get('code');
        $card_model    = new CardModel();
        $wx_card_service = new Card();
        $mch_name      = '';

        $wx_card_data = $wx_card_service->getCardByCode($code);

        $wx_card_id = $wx_card_data->get('card')['card_id'];

        $card_row = $card_model->where('card_id', $wx_card_id)->first();

        if(!$card_row){
            throw new CardException("卡券不存在：" . $code, Code::not_exists);
        }

        if($card_row->type != CardTypes::member_card){
            throw new CardException("不是会员卡", Code::invalid_param);
        }

        if($card_row->mch_id){
            $mch_model = new Mch();
            $mch_row   = $mch_model->getInfo($card_row->mch_id);
            $mch_name  = $mch_row->name;
        }

        $wx_card = $wx_card_service->get($card_row->card_id);


        return Response::success('', [
            'card_code'    => $code,
            'card_id'      => $card_row->id,
            'mch_id'       => $card_row->mch_id,
            'mch_name'     => (string)$mch_name,
            "not_overdue"  => $card_row->not_overdue,
            'begin_time'   => (int)$card_row->begin_time,
            'end_time'     => (int)$card_row->end_time,
            'logo_url'     => $card_row->logo_url,
            'title'        => $card_row->title,
            'notice'       => $wx_card['base_info']['notice'],
            'description'  => $wx_card['base_info']['description'],
        ]);

    }

    public function consume(ApiVerifyRequest $request)
    {
        $code       = $request->get('card_code');
        $card_id    = $request->get('card_id');

        $card_actor = new CardActor('', $card_id);

        $card_actor->canUse();

        Mch::ifStopThrow($this->user()->getMchId());

        $card_actor->checkMch($this->user()->getMchId());

        $card_actor->consume($code, OutStrTypes::outer_str_card_consume_exe_consume . ':' . $this->getCashierId() . '_' . $this->getExeId());

        return Response::success("核销成功");
    }

    public function consumeList(Request $request)
    {
        $pay_consume_log_model = new PayCardConsumeLog();

        $list = $pay_consume_log_model->limit($this->limitNum(), $this->user()->getAttribute('store_id'), (new Collection($request)));

        return Response::success('', $list);
    }

    public function scanCode(ApiVerifyRequest $request)
    {
        $code = $request->get('code');

        $code_service = new CodeService();

        $code_service->scan($code);

        return Response::success();
    }




}