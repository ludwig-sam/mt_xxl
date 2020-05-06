<?php namespace App\Service\Wechat\Hook;



use Abstracts\ReplyMessageInterface;
use App\Models\MchModel;
use App\Service\Wechat\Hook\Contracts\PaySuccessExtendsAbstracts;


class PaySuccessMchHook  extends PaySuccessExtendsAbstracts {


    public function name()
    {
        return 'pay_mch_execute';
    }

    public function do(ReplyMessageInterface $message)
    {
        $mch_model = new MchModel();

        $mch_model->incrementTranscationNumber($message->getAttr('mch_id'));

        $this->success();
    }



}