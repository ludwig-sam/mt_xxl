<?php namespace App\Http\Controllers\Minipro;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Minipro\MemberConfig;
use App\Service\Member\MemberAccountConfigService;
use App\Service\Sms\SmsVerifyCode;

class MemberAccountConfigController extends BaseController
{

    public function rule()
    {
        return new MemberConfig();
    }

    public function service():MemberAccountConfigService
    {
        return $this->single(function () {
            return new MemberAccountConfigService($this->user());
        });
    }

    private function verifyOldPwd($old_pwd)
    {
        if ($this->notVerify()) return false;

        return boolval($old_pwd);
    }

    private function notVerify()
    {
        return !$this->service()->hasPayPwd();
    }

    private function verifyMobile($old_pwd)
    {
        if ($this->notVerify()) return false;

        return !$this->verifyOldPwd($old_pwd);
    }

    public function updatePayPwd(ApiVerifyRequest $request)
    {
        $old_pwd     = $request->get('old_pwd');
        $mobile      = $request->get('mobile');
        $verify_code = $request->get('verify_code');

        $this->verifyOldPwd($old_pwd) && $this->service()->checkOldPwd($old_pwd);

        $this->verifyMobile($old_pwd) && SmsVerifyCode::verifyAndDel($mobile, $verify_code);

        $ret = $this->service()->update(null, $request);

        return self::response($ret, '修改密码');
    }

    public function isHasPayPwd()
    {
        $has_pwd = $this->service()->hasPayPwd();

        return self::success('', [
            'has_pay_pwd' => (bool)$has_pwd
        ]);
    }

}