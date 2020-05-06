<?php namespace App\Service\Wechat\Hook;


use App\Models\CardCodeModel;
use App\DataTypes\OutStrTypes;
use App\Models\PayOrderDetailModel;
use App\Service\Wechat\Card;
use Abstracts\ReplyMessageInterface;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;
use App\Service\Wechat\Hook\Traits\ConsumeLogTrait;

class PaySuccessConsumeCardHook  extends PaySuccessExtendsAbstracts{

    use ConsumeLogTrait;

    public function name()
    {
        return 'card_consume_execute';
    }

    public function do(ReplyMessageInterface $message)
    {

        $order_detail_model = new PayOrderDetailModel();

        $detail_row    = $order_detail_model->where('order_id', $this->orderId())->first();

        $couponCode    = $detail_row->coupon_code;
        $wechatService = new Card();

        if(!$couponCode){
            $this->success();
            return ;
        }

        if(!$wechatService->consume($couponCode, null, OutStrTypes::outer_str_card_consume_exe_pay)){
            $this->throw("卡券核销失败");
        }

        $this->saveLog($detail_row->coupon_id, $couponCode, OutStrTypes::outer_str_card_consume_exe_pay);

        $this->consumeStoreLog($detail_row->coupon_id, $couponCode);

        $this->success();
    }

    function orderId()
    {
        return $this->message->getAttr('id');
    }

    function storeId()
    {
        return $this->message->getAttr('store_id');
    }

    function opratorId()
    {
        return $this->message->getAttr('cashier_id');
    }

    function exeId()
    {
        return $this->message->getAttr('exe_id');
    }

    function memberId()
    {
        return (int)$this->message->getAttr('member_id');
    }

    function orderNo()
    {
        return $this->message->getAttr('order_no');
    }

    function mchId()
    {
        return $this->message->getAttr('mch_id');
    }

    function consumeStoreLog($card_id, $code)
    {
        $code_model = new CardCodeModel();

        $code_model->where('card_id', $card_id)->where('code_no', $code)->first()->update([
            'consume_mch_id'   => $this->mchId(),
            'consume_store_id' => $this->storeId()
        ]);
    }

}