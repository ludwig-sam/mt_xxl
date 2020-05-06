<?php namespace App\Http\Controllers\Admin;

use App\Exceptions\ReplyException;
use App\Http\Codes\Code;
use Libs\Arr;
use Libs\Response;
use Libs\Str;
use App\Models\ReplyKeywords;
use App\Models\ReplyMaterialModel;
use App\Models\ReplyModel;
use App\Service\Reply\ReplyFactory;
use \Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ReplyController extends BaseController {


    public function rule()
    {
    }

    private function check(Collection &$request, $isKeywords = false)
    {
        if($isKeywords){
            $keyword = $request->get('keywords');
            if(!$keyword){
                throw new ReplyException("请输入关键词");
            }
        }

        $materials = $request->get('materials');

        if(!$materials){
            throw new ReplyException("请选择素材");
        }

        if(count($materials) > 6){
            throw new ReplyException("自动回复最多支持6条素材");
        }

        $materialIds = array_column($materials, 'material_id');
        $uniqueMIds  = array_unique($materialIds);

        if(count($uniqueMIds) < count($materialIds)){
            throw new ReplyException("请不要重复选择素材");
        }

        Arr::format($materials, function ($v){
            if(!$v['material_id'] || !is_numeric($v['material_id'])){
                throw new ReplyException("请选择正确的素材");
            }
        });
    }

    public function create(Request $request)
    {
        $eventName       = $request->get('event_name');
        $reqCellection   = new Collection($request->all());

        $this->check($reqCellection);

        ReplyFactory::make($eventName)->crate($eventName, $reqCellection);

        return Response::success("添加成功");
    }

    public function update($id, Request $request)
    {
        $reqCellection   = new Collection($request->all());

        $this->check($reqCellection);

        $reply_row = $this->checkAndGetRow($id);

        ReplyFactory::make(Str::first($reply_row->event_name, '_'))->update($id, $reqCellection);

        self::note('更新公众号自动回复', "更新了公众号自动回复ID:".$id);

        return Response::success("操作成功");
    }

    public function createKeywords(Request $request)
    {
        $keyword         = $request->get('keywords');
        $reqCellection   = new Collection($request->all());

        $this->check($reqCellection, true);

        $keywordsModel = new ReplyKeywords();

        $replyId    = ReplyFactory::make('text')->crate($keyword, $reqCellection);

        $affectRow  = $keywordsModel->create(['keyword' => $keyword, 'reply_id' => $replyId]);

        if(!$affectRow){
            return Response::error(Code::fail, '添加关键词失败');
        }

        return $this->update($replyId, $request);
    }

    public function get($id)
    {
        $replyModel = new ReplyModel();
        $reply      = $replyModel->getReplyDetail(['id' => $id]);

        if(!$reply){
            return Response::success('回复不存在', []);
        }

        return Response::success("", $reply);
    }

    public function getByEvent($event, Request $request)
    {
        $replyModel = new ReplyModel();

        $reply      = ReplyFactory::make($event);

        $event_name = $reply->getEventName();
        $event_key  = $reply->getEventKey();

        if($request->offsetExists('event_key')){
            $event_key = $request->get('event_key');
        }

        $replyInfo  = $replyModel->getReplyDetail(compact('event_name', 'event_key'));

        if(!$replyInfo){
            return Response::success('回复不存在', []);
        }

        return Response::success("", $replyInfo);
    }

    public function stopUse($id)
    {
        $reply      = $this->checkAndGetRow($id);

        $is_stop = (bool)$reply->is_stop;
        $is_stop = intval(!$is_stop);

        $reply->update([
            'is_stop' => $is_stop
        ]);

        self::note(($is_stop ? '停用' : '开启') . '回复', '事件名称：' . $reply->event_name . ':事件key' . $reply->event_key);

        return Response::success("修改成功", [
            'is_stop' => $is_stop
        ]);
    }

    private function isTextReply($reply_row)
    {
        return strtolower($reply_row->event_name) == 'text_';
    }

    private function checkAndGetRow($id)
    {
        $reply_model = new ReplyModel();

        $reply_row   = $reply_model->find($id);

        if(!$reply_row){
            throw new ReplyException('自动回复不存在', Code::not_exists);
        }

        return $reply_row;
    }

    public function delete($id)
    {
        $reply_row   = $this->checkAndGetRow($id);

        if(!$this->isTextReply($reply_row)){
            return Response::error(Code::sys_err, '非关键词回复不能删除');
        }

        $keywords_model = new ReplyKeywords();
        $keywords_model->where('reply_id', $reply_row->id)->delete();

        $reply_material_model = new ReplyMaterialModel();
        $reply_material_model->where('reply_id', $reply_row->id)->delete();


        $reply_row->delete();

        self::note('删除自动回复', '事件名称：' . $reply_row->event_name . ':事件key' . $reply_row->event_key);

        return Response::success('删除成功');
    }

}