<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午3:40
 */

namespace Libs\Payments\Special;


use App\Exceptions\CardException;
use App\Service\Account\FictitiousCardCodeService;
use App\Service\Row\RechargeRow;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PayableInterface;
use App\Service\Row\FictitousCardRow;
use Libs\Payments\Special\Support\Support;


class FictitiousPayment extends Pay implements PayableInterface
{

    public function getChannel()
    {
    }

    public function getTradeType()
    {
    }

    public function pay(Array $payload, Collection $params):Collection
    {
        $code_service = new FictitiousCardCodeService();
        $card_no      = $params->get('card_no');
        $card_pwd     = $params->get('card_pwd');
        $code         = $code_service->getCode($card_no, $card_pwd);
        $card         = new FictitousCardRow($code->card_id);
        $order_id     = $params->get('id');

        $ret = $code_service->cardToUsed($card, $code);
        if (!$ret) {
            throw new CardException('消费失败');
        }

        Support::rechargeSuccess($order_id, [
            'amount'  => $card->amount(),
            'card_no' => $card_no
        ]);

        $order = new RechargeRow($order_id);

        systemEvent($order, 'recharge');

        return new Collection();
    }

}