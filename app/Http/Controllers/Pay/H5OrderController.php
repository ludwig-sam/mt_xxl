<?php namespace App\Http\Controllers\Pay;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Exceptions\PayPaymentException;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Pay\H5PayRule;
use App\Service\Pay\PayService;
use App\Service\Row\OrderRow;
use App\Service\Users\MemberUser;
use Illuminate\Support\Collection;
use Libs\Pay;
use Libs\Response;
use App\Models\CardCodeModel;
use App\DataTypes\CardTypes;
use App\Models\ExeModel;
use App\Models\MchModel;
use App\Models\MemberModel;
use App\DataTypes\OutStrTypes;
use App\Models\StoreModel;
use App\Service\Auth\MemberCode;
use App\Service\Card\States\CardActor;
use App\Service\Mch\Mch;
use App\Service\Member\Member;
use App\Service\MemberLevel\MchMemberLevel;
use App\Service\Pay\JobService;
use App\Service\Pay\Payment;
use Illuminate\Http\Request;
use Providers\RequestOffsetableAdapter;

class H5OrderController extends BaseController
{


    private $member_id;

    public function rule()
    {
        return new H5PayRule();
    }

    public function login($exe_id, ApiVerifyRequest &$request)
    {
        $exe_model = new ExeModel();
        $exe_row   = $exe_model->find($exe_id);

        if (!$exe_row) {
            throw new PayPaymentException('收银台不存在');
        }

        $this->cardParma($request);

        $this->memberLogin($request);

        $request->offsetSet('mch_id', $exe_row->mch_id);
        $request->offsetSet('store_id', $exe_row->store_id);
        $request->offsetSet('exe_id', $exe_id);
        $request->offsetSet('order_no', 'h5' . Pay::orderNo());
        $request->offsetSet('subject', '在线支付');
    }

    private function memberLogin(ApiVerifyRequest &$request)
    {
        $member_service = new Member();
        $membmer_id     = $this->memberId($request);
        $member_service->loginById($membmer_id);
        $request->offsetSet('member_id', $membmer_id);
    }

    private function cardParma(Request &$request)
    {
        $code_id = $request->get('code_id');

        if ($code_id) {
            $card_model = new CardCodeModel();
            $code_row   = $card_model->find($code_id);

            if ($code_row) {
                $request->offsetSet('coupon_id', $code_row->card_id);
                $request->offsetSet('coupon_code', $code_row->code_no);
            }
        }

        $member_id = $this->memberId($request);

        if ($member_id) {

            $member_service = new Member();
            $member_row     = $member_service->getMemberAndCheck($member_id);

            $m_card_id   = $member_row->member_card_code_id;
            $m_card_code = $member_row->member_card_code;

            if ($m_card_id) {
                $card_model = new CardCodeModel();

                $request->offsetSet('card_id', $card_model->getCardIdByCodeId($m_card_id));
                $request->offsetSet('card_code', $m_card_code);
            }
        }
    }

    private function memberId(Request $request)
    {
        if ($this->member_id) return $this->member_id;

        $one_pwd = new MemberCode();

        return $this->member_id = $one_pwd->decode($request->get('key'));
    }

    public function create(ApiVerifyRequest $request)
    {
        $service = new Payment();

        $this->login($request->get('exe_id'), $request);

        $request_offset = new RequestOffsetableAdapter($request);
        $payConfigParam = $service->checkPay($request_offset);
        $order_row      = $service->createOrder($request_offset);
        $order_info     = $service->pay($order_row->id, $payConfigParam);
        $order          = new OrderRow($order_row->id);

        $this->job($order);

        return Response::success('', $order_info);
    }

    public function job(OrderRow $order)
    {
        $job_service = new JobService();

        $job_service->job($order);
    }

    public function cardList(ApiVerifyRequest $request)
    {
        $member_id       = $this->memberId($request);
        $card_code_model = new CardCodeModel();

        $mch_id = $this->mchId($request);

        return Response::success('', $card_code_model->validCardList($member_id, $this->limitNum(), $mch_id, [
            CardTypes::cash,
            CardTypes::discount
        ]));
    }

    public function cardGroupCards(ApiVerifyRequest $request)
    {
        $member_id       = $this->memberId($request);
        $card_code_model = new CardCodeModel();

        $mch_id = $this->mchId($request);

        return Response::success('', $card_code_model->validCardList($member_id, $this->limitNum(), $mch_id, [
            CardTypes::gift,
            CardTypes::groupon,
            CardTypes::general_coupon
        ]));
    }

    private function mchId(Request $request)
    {
        $exe_model = new ExeModel();
        $exe_row   = $exe_model->find($request->get('exe_id'));

        if (!$exe_row) {
            throw new ExceptionCustomCodeAble("收银台不存在");
        }

        return $exe_row->mch_id;
    }

    public function memberInfo(ApiVerifyRequest $request)
    {
        $member_model = new MemberModel();

        $member_level = new MchMemberLevel($this->mchId($request), 0);

        $member = $member_model->find($this->memberId($request));

        $dicount = $member_level->getLevels();

        if (!$member) {
            return Response::success('', ['discount' => $dicount, 'member' => []]);
        }

        return Response::success('', ['discount' => $dicount, 'member' => $member]);
    }

    public function calculation(ApiVerifyRequest $request)
    {
        $paymentService = new Payment();

        $this->login($request->get('exe_id'), $request);

        return Response::success('', ['total_amount' => $request->get('total_amount'), 'amount' => $paymentService->calculationAmount(new RequestOffsetableAdapter($request)),]);
    }

    public function store(ApiVerifyRequest $request)
    {

        $exe_model   = new ExeModel();
        $store_model = new StoreModel();
        $mch_model   = new MchModel();

        $exe_row = $exe_model->find($request->get('exe_id'));

        if (!$exe_row) {
            throw new PayPaymentException('收银台不存在');
        }

        $store = $store_model->find($exe_row->store_id);
        $mch   = $mch_model->find($exe_row->mch_id);

        if (!$store) {
            throw new PayPaymentException('门店不存在');
        }

        if (!$mch) {
            throw new PayPaymentException('商户不存在');
        }

        return Response::success('', ['mch' => ['id' => $mch->id, 'name' => $mch->name, 'logo' => $mch->logo, 'mch_category_name' => $mch->mch_category_name, 'mch_category_id' => $mch->mch_category_id, 'description' => $mch->description,], 'store' => ['id' => $store->id, 'pic' => $store->pic, 'name' => $store->name, 'address' => $store->address],]);
    }

    public function consume(ApiVerifyRequest $request)
    {
        $code    = $request->get('card_code');
        $card_id = $request->get('card_id');
        $mch_id  = $this->mchId($request);

        $card_actor = new CardActor('', $card_id);

        $card_actor->canUse();

        Mch::ifStopThrow($mch_id);

        $card_actor->checkMch($mch_id);

        $card_actor->consume($code, OutStrTypes::outer_str_card_consume_h5);

        return Response::success("核销成功");
    }

    public function surePay(ApiVerifyRequest $request)
    {
        $this->memberLogin($request);

        $pay_service = new PayService();
        $order_no    = $request->get('order_no');

        $pay_service->specialSurePay($order_no, self::member(), new Collection($request));

        return self::success('支付成功');
    }

}