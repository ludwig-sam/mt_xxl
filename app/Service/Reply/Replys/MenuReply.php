<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:48
 */

namespace App\Service\Reply\Replys;

use App\Models\WechatMenuModel;
use App\Service\Reply\Contracts\ReplySetAble;
use Illuminate\Support\Collection;


class MenuReply extends GeneralReply implements ReplySetAble
{

    private $event_key;

    public function getEventKey()
    {
        return $this->event_key;
    }

    public function event()
    {
        $menu_model = new WechatMenuModel();
        $menu       = $menu_model->getByKey($this->getEventKey());

        $event_name = $menu->type;

        return $event_name;
    }

    public function crate($name, Collection $collection)
    {
        $this->event_key = $collection->get('event_key');

        return parent::crate($name, $collection);
    }

}