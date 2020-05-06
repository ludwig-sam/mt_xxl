<?php namespace App\Http\Controllers\Minipro;


use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Filter;
use Libs\Log;
use Libs\Response;
use Libs\Time;
use App\Models\CardCodeModel;
use App\Models\CardModel;
use App\Service\Card\States\CardActor;
use App\Service\Member\Member;
use App\Service\Sms\SmsVerifyCode;
use App\Service\Wechat\Card;
use Illuminate\Http\Request;

class WechatCardController extends BaseController {

    private $model;
    private $memberCardId;
    private $code;

    public function rule()
    {

    }

    public function __construct(CardModel $model)
    {
        parent::__construct();

        $this->model = $model;

    }

    public function cardActivate(Request $request)
    {
        return view('card.activate', [
            'card_id'       => $request->get('card_id'),
            'encrypt_code'  => $request->get('encrypt_code'),
        ]);
    }

    private function getParam(Request &$request){
        $mobile = $request->get("mobile");
        $sex        = Filter::int($request->get('sex'));
        $idCard     = Filter::string($request->get('id_card'));
        $personName = Filter::string($request->get('person_name'));
        $profession = Filter::int($request->get('profession'));
        $birthDay   = Filter::string($request->get('birth_day'));
        $birthDay   = $birthDay ? : Time::date();

        $card_code_model = new CardCodeModel();
        $code_id         = $card_code_model->where('card_id', $this->memberCardId)->where('code_no', $this->code)->value('id');

        return [
            'mobile'         => $mobile,
            'sex'            => $sex,
            'birth_day'      => $birthDay,
            'id_card'        => $idCard,
            'person_name'    => $personName,
            'member_card_code'      => $this->code,
            'member_card_code_id'   => $code_id,
            'profession'            => $profession
        ];
    }

    private function getInterests(Request &$request){
        $interest   = $request->get('interest');
        return is_array($interest) ? $interest : [];
    }

    private function active(CardActor &$cardActor){
        $cardActor->activate($this->code);
        if(!$cardActor->result()->isSuccess()){
            return false;
        }
        return true;
    }

    private function deCode(Card &$cardService, $enCode, $cardCode){
        if($cardCode){
            $this->code = $cardCode;
            return true;
        }

        if(!$cardService->deCode($enCode)){
            return false;
        }
        $this->code         = $cardService->result()->getData()->get('code');
        return true;
    }

    public function memberActivate(ApiVerifyRequest $request)
    {
        $cardId    = $request->get('card_id');
        $enCode    = $request->get('encrypt_code');
        $card_code = $request->get('card_code');

        $memberService = new Member();
        $cardService   = new Card();
        $cardActor     = new CardActor($cardId);

        if($this->user()->isMember()){
            return Response::success('你已经是会员');
        }

        if(!$cardInfo = $cardActor->getCardInfo()){
            return Response::error($cardActor->result()->getCode(), $cardActor->result()->getMsg());
        }

        $this->memberCardId = $cardInfo['id'];

        if(!$this->deCode($cardService, $enCode, $card_code)){
            return Response::error(Code::decrypt_code_fall, $cardService->result->getMsg());
        }

        if(!$this->active($cardActor)){
            return Response::error(Code::activate_fail, $cardActor->result->getMsg());
        }

        if(!$memberService->becomeMember($this->user(), $this->getParam($request))){
            return Response::success($memberService->result->getMsg());
        }

        if(!$memberService->addInterest($this->user()->getId(), $this->getInterests($request))){
            Log::warning("兴趣添加失败", ['member_id' => $this->user()->getId()]);
        }

        $memberService->registeReward($this->user(), $this->memberCardId);

        return Response::success('激活成功');
    }
}