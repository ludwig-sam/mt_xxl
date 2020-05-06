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
use App\Models\ReplyKeywords;
use App\Models\ReplyModel;
use App\Service\Reply\Contracts\BaseReply;
use App\Service\Reply\Contracts\ReplySetAble;
use Illuminate\Support\Collection;

class TextReply extends BaseReply implements ReplySetAble
{

    public function event()
    {
        return '';
    }

    public function msgType()
    {
        return 'text';
    }

    public function crate($keyword, Collection $collection)
    {
        $eventName  = $this->getEventName();

        $this->check($keyword);

        $replyModel = new ReplyModel();

        if(!$replyModel->create([
            'event_name' => $eventName,
            'event_key'  => $keyword
        ], [])){
            throw new ReplyException('添加失败', Code::fail);
        }

        return $replyModel->id;
    }

    private function check($keyword)
    {
        $keywordsModel = new ReplyKeywords();

        if($keywordsModel->where('keyword' , $keyword)->value('id')){
            throw new ReplyException('关键词已经存在', Code::invalid_param);
        }

    }

    private function updateCheck($id, $keyword)
    {
        $keywordsModel = new ReplyKeywords();
        $reply_id      = $keywordsModel->where('keyword' , $keyword)->value('reply_id');

        if($reply_id && $id != $reply_id){
            throw new ReplyException('关键词已经存在', Code::invalid_param);
        }
    }

    public function update($id, Collection $collection)
    {
        $materials = $collection->get('materials');

        $keyword = $collection->get('keywords');

        $this->updateCheck($id, $keyword);

        $replyModel = new ReplyModel();

        $reply_row  = $replyModel->find($id);

        $keywordsModel = new ReplyKeywords();
        $keywordsModel->where('reply_id', $id)->where('keyword', $reply_row->event_key)->update([
            'keyword' => $keyword
        ]);

        $reply_row->event_key = $keyword;
        $reply_row->save();

        if(!$replyModel->edit($id, $materials)){
            throw new ReplyException('更新失败', Code::fail);
        }
    }

}