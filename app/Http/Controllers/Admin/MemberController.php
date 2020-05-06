<?php namespace App\Http\Controllers\Admin;


use App\Http\Codes\Code;
use App\Http\Codes\LeiCode;
use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Arr;
use Libs\Response;
use App\Models\MemberModel;
use App\DataTypes\MessageSendLogTypes;
use App\DataTypes\MessageSendTypes;
use App\Repositorys\Admin\MemberRepository;
use App\Repositorys\Admin\PayOrderRepository;
use App\Service\Listener\CardSendListener;
use App\Service\Card\CardService;
use App\Service\Export\OfficialExcel;
use App\Service\Listener\MessageSendListener;
use App\Service\Export\Export;
use App\Service\MessageSend\MessageService;
use App\Service\Template\TemplateRow;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MemberController extends BaseController{


    private $repository;

    public function rule()
    {
        return  new Rules\Admin\Member();
    }

    public function __construct(MemberRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function update(ApiVerifyRequest $request)
    {
        if($this->repository->find(intval($request['id'])))
        {
            return Response::error(LeiCode::not_exists, '用户不存在');
        }
        if(!$this->repository->update($request['id'],$request->all())){
            return Response::error(LeiCode::Member_update_fail, '网络错误');
        }
        return Response::success('');
    }

    public function show($id){
	    if(!$this->repository->find(intval($id))){
	    	return Response::error('','此用户不存在');
		}
        if(!$data=$this->repository->show(intval($id))){
            return Response::error(LeiCode::Member_show_fail, '网络错误');
        }
        return Response::success('',$data);
    }

	public function lists(ApiVerifyRequest $request){
		return Response::success('', $this->repository->limit($this->limitNum(), $request));
	}

	public function exportSelect(ApiVerifyRequest $request)
    {
        $ids      = $request->get('ids');

        $ids      = is_array($ids) ? $ids : explode(',', $ids);

        $fileName = $request->get('file_name');

        if(!$ids){
            return Response::error(Code::not_exists, "请选择要导出的会员");
        }

        $export_service = new Export($fileName, new OfficialExcel);

        return $export_service->exportById($this->repository, $ids, $request);
    }

    public function exportFilter(ApiVerifyRequest $request)
    {
        $fileName = $request->get('file_name');

        $export_service = new Export($fileName, new OfficialExcel);

        return $export_service->exportByFilter($this->repository, $request);
    }

    public function sendCardSelect(ApiVerifyRequest $request)
    {
        $memberModel = new MemberModel();
        $memberIds   = $request->get('member_id');
        $list        = $memberModel->whereIn("id", $memberIds)->select("person_name", "id", "openid")->get()->toArray();

        if(!$list){
            return Response::error(Code::not_exists, "没有任何一个会员");
        }

        if($notHaveOpenid =
            Arr::find($list, '', 'openid')){
            return Response::error(Code::invalid_param, $notHaveOpenid['person_name'] . "没有openid");
        }

        $request->query->set('send_by', 'openid');
        $request->query->set('to', array_column($list, 'openid'));

        return $this->sendCardToOpenid($request);
    }

    public function sendCardFilter(ApiVerifyRequest $request)
    {
        $list      = $this->repository->openidNoLimit(new Collection($request->get('filter', [])))->toArray();

        $openids   = array_column($list, 'openid');

        $request->query->set('send_by', 'openid');
        $request->query->set('to', $openids);

        return $this->sendCardToOpenid($request);
    }

    public function sendTemplateSelect(ApiVerifyRequest $request)
    {
        $memberModel = new MemberModel();
        $memberIds   = $request->get('member_id');
        $t_id        = $request->get('t_id');
        $list        = $memberModel->whereIn("id", $memberIds)->select("openid")->get()->toArray();
        $open_ids    = array_column($list, 'openid');

        $request->query->set('to', $open_ids);

        return $this->sendTemplateToOpenid($t_id, $request);
    }

    public function sendTemplateFilter(ApiVerifyRequest $request)
    {
        $t_id      = $request->get('t_id');

        $list      = $this->repository->openidNoLimit(new Collection($request->get('filter', [])))->toArray();

        $openids   = array_column($list, 'openid');

        $request->query->set('to', $openids);

        return $this->sendTemplateToOpenid($t_id, $request);
    }

    public function sendTemplateToOpenid($t_id, Request $request)
    {
        $to             = $request->get('to');

        $messageService = (new MessageService($this->user()))->init(MessageSendTypes::type_template, 'openid', 'general');

        $template_row_service = new TemplateRow($t_id);

        $messageService->sendByAsync($to, $template_row_service->getSendParsePlaceholder([]), new MessageSendListener());

        return Response::error($messageService->result()->getCode(), $messageService->result()->getMsg());
    }

    public function sendCardToOpenid(Request $request)
    {
        $way         = $request->get('way');
        $wxCardId    = $request->get('wx_card_id');
        $to          = $request->get('to');
        $sendBy      = $request->get('send_by');

        MessageSendLogTypes::checkType($way);

        $data    = [
            'card_id' => $wxCardId
        ];

        $messageService = (new MessageService($this->user()))->init($way, $sendBy, 'card');

        $isSync = ($way == MessageSendTypes::type_mass);

        if($isSync){
            $messageService->sendBySync($to, $data);
        }else{
            $listener = new CardSendListener();
            $listener->wxCardId =  $wxCardId;
            $listener->number   =  1;
            $messageService->sendByAsync($to, $data, $listener);
        }

        if($messageService->result()->isSuccess()){
            if($isSync){
                CardService::grantLog(null, $wxCardId, count($to));
            }
            return Response::success($messageService->result()->getMsg());
        }

        return Response::error($messageService->result()->getCode(), $messageService->result()->getMsg());
    }

	public function memberPayOrder($id,PayOrderRepository $pay_order_repository)
    {
		if(! $this->repository->find($id)){
			return Response::error('','该用户不存在');
		}
		return Response::success('',$pay_order_repository->memberPayOrder($id,$this->limitNum()));
	}

	public function updateOne(ApiVerifyRequest $request)
	{
		if(!$this->repository->find(intval($request->get('id')))){
			return Response::error('','该用户不存在');
		}
		$data = Arr::getIfExists($request->all(),['id','person_name','mobile','sex','id_card','birth_day','profession','level','point','balance']);
		if(!$id = $this->repository->updateOne($data)){
			return Response::error(WeiCode::update_member_fail, '网络错误');
		}

        self::note('更新用户', $request['id']);

		return Response::success('更新成功');
	}

}