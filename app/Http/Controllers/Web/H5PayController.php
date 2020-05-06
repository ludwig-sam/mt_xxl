<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\MemberModel;
use App\PayConfig;
use App\Service\Auth\MemberCode;
use App\Service\Member\Member;
use App\Service\Wechat\Auth;
use Illuminate\Http\Request;
use App\Service\Wechat\User;
use Illuminate\Support\Collection;


class H5PayController extends Controller
{

    public function module()
    {
        return 'web';
    }

    public function rule()
    {
    }

    private function redirectUrl(Request $request)
    {
        $url = '/wxpay/index.html';

        if($this->isAlipayClient($request)){
            $url = '/alipay/index.html';
        }

        $param = $request->all();
        $param['t'] = time();

        return $url . '?' . http_build_query($param);
    }

    public function isAlipayClient(Request $request)
    {
        $user_agent = $request->server->get('HTTP_USER_AGENT');

        if(strpos($user_agent, 'AplipayClient') !== false){
            return true;
        }

        if(strpos($user_agent, 'AliApp') !== false){
            return true;
        }

        return false;
    }

    private function authCompleteRedirect(Request $request, $uid)
    {
        $one_pwd = new MemberCode();

        $request->offsetSet('key', $one_pwd->encode(intval($uid)));

        return redirect($this->redirectUrl($request));
    }

    private function login(Collection $user_info, $unionid)
    {
        $member_service = new Member();

        $user_info['unionid'] = $unionid;

        $member_service->pubLogin($user_info);

    }

    private function getMemberRowByWechatInfo(Collection $user_info, $openid)
    {
        $user_service = new User();

        $member_model = new MemberModel();

        $unionid = $user_service->openidToUnionid($openid);

        $this->login($user_info, $unionid);

        $member_row = $member_model->getByUnionId($unionid);

        if(!$member_row){
            throw new \Exception("会员不存在");
        }

        return $member_row;
    }

    private function wechatScan(Request $request)
    {
        $wechat_user = new Auth();

        return $wechat_user->base($request->getUri(), function($user_info) use ($request){

            $user_info = new Collection($user_info);

            $openid = $user_info->get('id');
            $uid = 0;

            if($openid){
                $member_row = $this->getMemberRowByWechatInfo($user_info, $openid);
                $uid = intval($member_row->id);
            }

            return $this->authCompleteRedirect($request, $uid);
        });
    }

    private function apliScan(Request $request)
    {
        $request->offsetSet('channel', PayConfig::PAYMENT_UPAY_ALIPAY_SCAN_CODE);

        return $this->authCompleteRedirect($request, 0);
    }

    public function scanCode(Request $request)
    {

        if($this->isAlipayClient($request)){
            $url = $this->apliScan($request);
        }else{
            $url = $this->wechatScan($request);
        }

        return $url;
    }

    public function pay(Request $request)
    {
        return view('pay.pay', ['key' => $request->get('key'), 'exe_id' => $request->get('exe_id')]);
    }

}
