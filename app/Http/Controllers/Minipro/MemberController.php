<?php namespace App\Http\Controllers\Minipro;

use App\Http\Codes\Code;
use App\Http\Codes\LeiCode;
use App\Http\Rules\Minipro\MemberRule;
use Libs\Response;
use App\Models\CardCodeModel;
use App\Models\CardModel;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use App\Models\MemberAccountLogModel;
use App\Models\MemberMCardInfoModel;
use App\Models\MemberModel;
use App\DataTypes\OutStrTypes;
use App\Models\PayOrderModel;
use App\Models\RewardsModel;
use App\Repositorys\Admin\MemberRepository;
use App\Service\Card\CardService;
use App\Service\Member\RegisteReward;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Service\Card\States\CardActor;
use App\Http\Requests\ApiVerifyRequest;

class MemberController extends BaseController {


	private $repository;

	public function rule()
	{
        return new MemberRule();
	}

	public function __construct(MemberRepository $repository)
	{
		parent::__construct();
		$this->repository = $repository;
	}

    public function pointList()
    {
        $accountLogModel = new MemberAccountLogModel();
        $list = $accountLogModel->getPointLimitByMember($this->user()->getId(), $this->limitNum());

        return Response::success('', $list);
    }

    public function payList(Request $request)
    {
        $payModel = new PayOrderModel();
        $req_collection = new Collection($request);

        $list       = $payModel->myAccountList($this->user()->getId(), $this->limitNum(), $req_collection);

        $result = $list->toArray();

        $result['total'] = $payModel->myAccountTotal($this->user()->getId(), $req_collection);
        $result['count'] = $payModel->myAccountCount($this->user()->getId(), $req_collection);

        return Response::success('', $result);
    }

    public function myInfo()
    {
        $memberModel  = new MemberModel();
        $interest     = $memberModel->getIntrest($this->user()->getId());

        $memberInfo    = $this->user()->toArray();
        $profession    = $memberModel->getProfessionName($memberInfo['profession']);

        $memberInfo['profession_name'] = $profession ? $profession->name : '';
        $memberInfo['interest']        = $interest->toArray();
        $memberInfo['member_card_id']      = $this->getMemberCardField($memberInfo['member_card_code_id'], 'id');
        $memberInfo['member_wx_card_id']   = $this->getMemberCardField($memberInfo['member_card_code_id'], 'card_id');

        return Response::success('', $memberInfo);
    }

    private function getMemberCardField($code_id, $field)
    {
        static $card_row;

        if(!$code_id)return '';

        if(!$card_row){
            $card_service = new CardService();
            $card_row = $card_service->getCardByCodeId($code_id);
        }

        return $card_row->$field;
    }

    public function prepareReceiveMCard()
    {
        $cardModel = new CardModel();

        $cardInfo  = $cardModel->where("status", CardStatus::sending)->orderBy('id', 'desc')->where("type", CardTypes::member_card)->first();

        if(!$cardInfo){
            return Response::error(Code::not_exists, "没有可以领取的会员卡");
        }

        if($cardInfo['quantity'] <= 0){
            return Response::error(Code::not_exists, "会员卡库存不足");
        }

        $cardActor         = new CardActor('', $cardInfo['id']);
        $cardData          = $cardActor->grant(OutStrTypes::outer_str_card_receive_miniprogram);

        return Response::success('', [
            'card_data'    => $cardData
        ]);
    }

	public function update(ApiVerifyRequest $request)
	{
		if(!$this->repository->find(intval($this->user()->getId()))){
			return Response::error(LeiCode::not_exists,'此用户不存在');
		}
		if(!$data = $this->repository->update(intval($this->user()->getId()),$request->all())){
			return Response::error(LeiCode::Member_update_fail, '网络错误');
		}
		return Response::success('');
	}

	public function show(){
		if(!$this->repository->find(intval($this->user()->getId()))){
			return Response::error('','此用户不存在');
		}
		if(!$data=$this->repository->show(intval($this->user()->getId()))){
			return Response::error(LeiCode::Member_show_fail, '网络错误');
		}
		return Response::success('',$data);
	}

	public function getRegisteReward()
    {
        $rewards_service = new RegisteReward($this->user());
        $reward_row      = $rewards_service->getRow();

        $rewards_service->check($reward_row);

        if($rewards_service->isReceive($reward_row)){
            return Response::error(Code::has_received, '已经领取过奖励了');
        }

        $card_actor = new CardActor(null, $rewards_service->getCardId($reward_row));

        return Response::success('', [
            'card_data'    => $card_actor->grant(OutStrTypes::outer_str_registe_reward)
        ]);
    }

    public function cardStatus(Request $request)
    {
        $code_id = $request->get('code_id');
        $code_model = new CardCodeModel();

        return Response::success('', [
            'status' => $code_model->getStatus($code_id)
        ]);
    }
}