<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/10
 * Time: 上午9:41
 */

namespace App\Service\Listener;


use Abstracts\ListenerInterface;
use App\Service\Card\CardService;

class CardSendListener implements ListenerInterface
{
    public $id;
    public $wxCardId;
    public $number;

    public function change($data)
    {
        CardService::grantLog($this->id, $this->wxCardId, $this->number);
    }
}