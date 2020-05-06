<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/6/30
 * Time: 下午2:41
 */

namespace App\Service\Activity;

use App\Exceptions\MemberException;
use App\Models\ExchangeModel;
use App\DataTypes\OutStrTypes;
use App\Service\Activity\Has\Def;
use App\Service\Card\States\CardActor;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;

class Exchange extends Service
{

    public function exchange($id, UserAbstraict &$user){
        $exchangeInfo   = $this->getExchangeInfo($id);

        if(!$exchangeInfo){
            throw new MemberException("兑换活动不存在:" . $id);
        }

        $this->newByName($exchangeInfo['exchange_name'])->check($exchangeInfo, $user);

        $systemCardId = $exchangeInfo['card_id'];

        $cardActor    = new CardActor('', $systemCardId);
        $grantData    = $cardActor->grant($this->getOutStr($id));

        return $grantData;
    }

    private function getOutStr($id)
    {
        return OutStrTypes::outer_str_card_receive_exchange . ':' . $id;
    }

    public function consumeSuccess($id, UserAbstraict &$user)
    {
        $exchangeInfo   = $this->getExchangeInfo($id);

        $exchangeName = $exchangeInfo['exchange_name'];
        $this->newByName($exchangeName)->exchange($exchangeInfo, $user);
    }

    public function getExchangeInfo($id)
    {
        $model          = new ExchangeModel();
        return $model->find($id);
    }

    private function newByName($name){

        $class = "App\\Service\\Activity\\Has\\" . ucfirst(strtolower($name));

        if(class_exists($class)){
            return new $class;
        }

        return new Def();
    }

}