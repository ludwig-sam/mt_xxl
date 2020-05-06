<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use Libs\Arr;
use Libs\Response;
use Libs\Time;
use App\Models\CardModel;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use App\Service\Wechat\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CardController extends BaseController {


    public function rule()
    {
    }

    public function list(Request $request){
        $type      = $request->get('type');

        if($type == CardTypes::member_card){
            return $this->memberCardList($request);
        }

        return $this->ticketList($request);
    }

    public function ticketList(Request $request)
    {
        $cardModel = new CardModel();

        $list = $cardModel->ticketLimit($this->limitNum(), new Collection($request->all()));

        return Response::success('', $list);
    }

    public function memberCardList(Request $request)
    {
        $cardModel = new CardModel();

        $list = $cardModel->memberCardLimit($this->limitNum(), new Collection($request->all()));

        return Response::success('', $list);
    }

    public function exchangeList(Request $request)
    {
        $cardModel = new CardModel();
        $type      = $request->get('type');

        $type && CardTypes::checkTypes($type);

        $list = $cardModel->exchangLimit($this->limitNum(), new Collection($request));

        return Response::success('', $list);
    }

    public function qrcode($id)
    {
        $cardModel = new CardModel();

        $cardRow = $cardModel->find($id);

        if(!$cardRow){
            return Response::error(Code::fail, '卡券不存在');
        }

        $wechatCardService = new Card();

        if(!$wechatCardService->qrcode($cardRow->card_id, 1800)){
            return Response::error(Code::wechat_error, $wechatCardService->result()->getMsg());
        }

        return Response::success('', [
            'show_qrcode_url' => $wechatCardService->result()->getData()->get('show_qrcode_url')
        ]);
    }

    public function useStatus($id, Request $request)
    {
        $status = $request->get('status');

        CardStatus::checkStatus($status);

        $cardModel = new CardModel();

        $row = $cardModel->find($id);

        if(!$row){
            return Response::error(Code::not_exists, '卡券不存在');
        }

        $row->status = $status;

        if(!$row->save()){
            return Response::error(Code::update_fail, '修改失败');
        }
	    $detial = "卡券ID:".$id."状态更改为:".$status;
		self::note('卡券状态更改',$detial);
        return Response::success("修改成功");
    }

    public function delete($id)
    {
        $cardModel = new CardModel();

        $row = $cardModel->find($id);

        if(!$row){
            return Response::error(Code::not_exists, "卡券不存在");
        }

        if(!$row->delete()){
            return Response::error(Code::fail, "删除失败");
        }
	    $detial = "删除卡券ID:".$id;
	    self::note('删除卡券',$detial);
        return Response::success("删除成功");
    }

}