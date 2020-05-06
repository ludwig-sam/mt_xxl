<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/5
 * Time: 下午3:57
 */

namespace App\Service\Wechat\Hook\Contracts;

use App\Exceptions\PayPaymentException;
use Abstracts\ReplyMessageInterface;
use Libs\Log;
use App\Models\PayOrderExtendsFunLogModel;
use App\Http\Codes\Code;
use App\DataTypes\PayOrderExtendsFunLogStatus;


abstract class PaySuccessExtendsAbstracts implements HookInterface
{

    /**
     * @var ReplyMessageInterface
     */
    protected $message;
    protected $row;

    public function hanlder(ReplyMessageInterface $message)
    {
        $this->message = $message;
        $this->row     = $this->getExtendsRow();
        if($this->checkBeforeDo()){
            Log::info('正在执行支付的钩子：' . $this->name());
            $this->do($message);
        }
    }

    public function setMessage(ReplyMessageInterface $message)
    {
        $this->message = $message;
    }

    private function getExtendsRow()
    {
        $orderId              = (int)$this->message->getAttr('id');
        $payOrderExtendsModle = new PayOrderExtendsFunLogModel();
        return $payOrderExtendsModle->where('order_id', $orderId)->first();
    }

    protected function throw($msg)
    {
        $this->fail();

        throw new PayPaymentException($msg, Code::pay_success_consume_card_fail, $this->message->toArray());
    }

    protected function success()
    {
        $this->row->{$this->name()} = PayOrderExtendsFunLogStatus::execute_status_success;
        $this->row->save();
    }

    protected function fail()
    {
        $this->row->{$this->name()} = PayOrderExtendsFunLogStatus::execute_status_fail;
        $this->row->save();
    }

    protected function checkBeforeDo()
    {
        $findRow = $this->getExtendsRow();

        if(!$findRow){
            $this->throw("未找到订单");
        }

        if($findRow->{$this->name()} == PayOrderExtendsFunLogStatus::execute_status_success){
            return false;
        }

        return true;
    }

    abstract function do(ReplyMessageInterface $message);
    abstract function name();
}