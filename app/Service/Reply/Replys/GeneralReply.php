<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 下午2:48
 */

namespace App\Service\Reply\Replys;

use App\Exceptions\ReplyException;
use App\Http\Codes\Code;
use App\DataTypes\ReplyEvents;
use App\Service\Reply\Contracts\BaseReply;
use App\Service\Reply\Contracts\ReplySetAble;
use Illuminate\Support\Collection;
use App\Models\ReplyModel;


class GeneralReply extends BaseReply implements ReplySetAble
{

    private $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function event()
    {
        return $this->event;
    }

    public function msgType()
    {
        return 'event';
    }

    public function crate($name, Collection $collection)
    {

        ReplyEvents::checkType($name);

        $materials = $collection->get('materials');
        $eventName  = $this->getEventName();
        $eventKey   = $this->getEventKey();

        $replyModel = new ReplyModel();

        $replyData  = $replyModel->where('event_name', $eventName)->where('event_key', $eventKey)->first();

        if($replyData){
            throw new ReplyException('回复配置已经存在', Code::fail, [
                'event_name' => $eventName,
                'event_key'  => $eventKey
            ]);
        }

        if(!$replyModel->create([
            'event_name' => $eventName,
            'event_key'  => $eventKey
        ], $materials)){
            throw new ReplyException('添加失败', Code::fail);
        }

        return $replyModel->id;
    }

    public function update($id, Collection $collection)
    {
        $materials = $collection->get('materials');

        $replyModel = new ReplyModel();

        $replyData  = $replyModel->where('id', $id)->first();

        if(!$replyData){
            throw new ReplyException('回复不存在', Code::fail, compact('id'));
        }

        if(!$replyModel->edit($id, $materials)){
            throw new ReplyException('更新失败', Code::fail);
        }
    }

}