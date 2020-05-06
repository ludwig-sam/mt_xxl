<?php namespace App\Service\Wechat;


use Tymon\JWTAuth\Claims\Custom;

class Message  extends Wechat {


    public function card($openid, $cardId)
    {
        return $this->catch(function () use ($openid, $cardId){
            $card = new \EasyWeChat\Kernel\Messages\Card($cardId);
            return $this->parseResult($this->serve()->customer_service->message($card)->to($openid)->send())->isSuccess();
        });
    }




}