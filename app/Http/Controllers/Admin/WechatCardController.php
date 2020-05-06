<?php namespace App\Http\Controllers\Admin;



use App\Http\Codes\Code;
use Libs\Response;
use App\Models\CardModel;
use App\DataTypes\CardTypes;
use App\Models\MchCardsModel;
use App\Service\Card\CardService;
use App\Service\Wechat\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WechatCardController extends BaseController {

    private $type   = '';
    private $wechatData = [];

    public function rule()
    {
    }

    public function create(Request $request)
    {
        $wechatCard    = new Card();
        $cardService   = new CardService();
        $request       = new Collection($request->all());
        $cardType      = $request->get("card_type");

        CardTypes::checkTypes($cardType);

        $this->wechatData = $cardService->parse($request, $cardType);

        $cardService->checkExchangeData($request);

        if(!$wechatCard->create($cardType, $this->wechatData)){
            return Response::error(Code::create_fial, $wechatCard->result()->getMsg());
        }

        $card_id               = $wechatCard->result()->getData()->get('card_id');

        $saveData              = $cardService->getSaveFieldsData($request);
        $saveData['card_id']   = $card_id;
        $saveData['mch_ids']   = $request->get('mch_ids', []);
        $saveData['date_info'] = $this->wechatData['base_info']['date_info'];

        if(!$id = $cardService->save($saveData)){
            return Response::error(Code::sys_err, '添加失败');
        }

        return Response::success('添加成功', ['card_id' => $card_id, 'id' => $id]);
    }

    public function update($id, Request $request)
    {
        $wechatCard        = new Card();
        $cardService       = new CardService();
        $request           = new Collection($request->all());
        $model             = new CardModel();
        $cardRow           = $model->find($id);

        $cardService->checkExchangeData($request);

        if(!$cardRow){
            return Response::error(Code::not_exists, '卡券不存在');
        }

        $type        = $cardRow->type;

        $wechatData  = $cardService->updateParse($request, $type);

        if(!$wechatCard->update($cardRow->card_id, $type, $wechatData)){
            return Response::error(Code::create_fial, $wechatCard->result()->getMsg());
        }

        $reqData               = $request->all();
        $reqData['wechat']     = $wechatData;
        $saveData              = $cardService->getSaveFieldsData($reqData);
        $saveData['mch_ids']   = $request->get('mch_ids', []);


        if(isset($wechatData['base_info']['date_info'])){
            $saveData['date_info'] = $wechatData['base_info']['date_info'];
        }

        if(!$cardService->updateSave($cardRow, $saveData)){
            return Response::error(Code::sys_err, '修改失败');
        }

	    self::note("更新微信会员卡:", "更新了微信会员卡ID:".$id);

        return Response::success('修改成功');
    }

    public function get($id)
    {
        $cardModel = new CardModel();
        $row       = $cardModel->getWithMch($id);
        $wechatService = new Card();

        if(!$row){
            return Response::error(Code::not_exists, "卡券不存在");
        }

        $data = $wechatService->get($row->card_id);

        $result = [
            'wechat'    => $data,
            'mch_name'  => $row['mch_name'],
            'card_type' => $row['type'],
            "mch_ids"   => (new MchCardsModel())->getMchs($id)
        ];

        return Response::success('', $result);
    }

    public function modifyStock($id, Request $request)
    {
        $quantity = (int)$request->get('quantity');
        $cardModel = new CardModel();
        $cardService = new Card();

        $cardRow = $cardModel->find($id);

        if(!$cardRow){
            return Response::error(Code::not_exists, '卡券不存在');
        }

        if($quantity < 0){
            return Response::error(Code::fail, '新库存数量不能小于0');
        }

        if($quantity == $cardRow->quantity)return Response::success('库存未变动');

        $chanQuantity             = $quantity - $cardRow->quantity;
        $cardRow->quantity        = $quantity;
        $cardRow->total_quantity += $chanQuantity;

        if(!$cardService->modifyStock($cardRow->card_id, $chanQuantity)){
            return Response::error(Code::wechat_error, $cardService->result()->getMsg());
        }

        if(!$cardRow->save()){
            return Response::error(Code::upload_fail, '更新失败');
        }

        return Response::success('更新成功');
    }

}