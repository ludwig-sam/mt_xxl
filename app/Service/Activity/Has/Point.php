<?php

namespace App\Service\Activity\Has;


use App\Exceptions\MemberException;
use Libs\Log;
use App\Models\CardModel;
use App\DataTypes\MessageSendRoots;
use App\Service\Account\Account;
use App\Service\Activity\Contracts\ExchangeAble;
use App\Service\MessageSend\Contracts\MessageProviderInterface;
use App\Service\MessageSend\MessageTirgger;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;

class Point implements ExchangeAble, MessageProviderInterface
{

    /**
     * @var UserAbstraict
     */
    private $user;

    private $message_param;

    public function getMessageTo()
    {
        $openid = $this->user->getAttribute('openid');

        if(!$openid) return [];

        return [$openid];
    }

    public function getMessageTemplateName()
    {
        return MessageSendRoots::point_exchange_gift_notify;
    }

    public function getMessageParam()
    {
        return (array)$this->message_param;
    }

    public function check($info, UserAbstraict &$user)
    {
        $exchangeValue = (int)$info['exchange_value'];
        $point = (int)$user->getAttribute('point');

        if($point < $exchangeValue){
            throw new MemberException("剩余积分{$point}不足以兑换");
        }
    }

    public function getName($info)
    {
        $info = new Collection($info);

        $card_model = new CardModel();

        return $card_model->getTitle($info->get('card_id'));
    }

    public function exchange($info, UserAbstraict &$user)
    {
        $this->user = $user;

        $old_point = $user->getAttribute('point');

        $exchangeValue = (int)$info['exchange_value'];

        $accountLog = new Account($user, Account::event_name_consume, Account::scene_name_exchange);

        $config = ['comment' => '积分兑换', 'order_id' => $info['id']];

        if(!$accountLog->pointReduce($exchangeValue, $config)){
            throw new MemberException("兑换失败", MemberException::update_member_fail);
        }

        $point = $user->getAttribute('point');

        $this->message_param = ['old_point' => $old_point, 'consume' => $exchangeValue, 'point' => $point, 'name' => $this->getName($info)];

        MessageTirgger::instance()->trigger($this);
    }
}