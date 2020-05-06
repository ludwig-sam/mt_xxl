<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/9
 * Time: 上午10:23
 */

namespace App\Service\Sms;


use Abstracts\SmsInterface;
use App\Exceptions\SmsException;
use App\Http\Codes\Code;
use Libs\Str;
use Illuminate\Support\Facades\Redis;

class SmsVerifyCode
{

    /**
     * @var SmsInterface
     */
    private        $sender;
    private static $expires;

    public function __construct()
    {
        $this->sender  = app(SmsInterface::class);
        self::$expires = config('sms.expires');
    }

    private function getCode()
    {
        return Str::rand(4, range(0, 9));
    }

    private static function getCacheName($number)
    {
        return config('sms.table') . $number;
    }

    private static function hasSend($number)
    {
        $name = self::getCacheName($number);

        return Redis::get($name);
    }

    private function save($number, $code)
    {
        $name = $this->getCacheName($number);

        Redis::set($name, $code);

        Redis::expire($name, self::$expires);
    }

    private static function delete($number)
    {
        $name = self::getCacheName($number);
        Redis::del($name);
    }

    public function send($number, $params = [])
    {
        if ($this->hasSend($number)) {
            throw new SmsException("请稍后重试");
        }

        $code = $this->getCode();

        if (!$this->sender->send($number, config('sms.sign'), '您的验证码是：' . $code, $params)) {
            throw new SmsException('短信发送失败', Code::sms_send_fail, ['request' => $this->sender->getRequest(), 'response' => $this->sender->getResponse()]);
        }

        $this->save($number, $code);

        return true;
    }

    public static function verifyAndDel($number, $code)
    {
        self::verify($number, $code);
        self::delete($number);

        return true;
    }

    public static function verify($number, $code)
    {
        $sendCode = self::hasSend($number);

        if (!$code) {
            throw new SmsException("验证码无效", Code::invalid_sms_verify_code, [
                'send_code'  => $sendCode,
                'input_code' => $code
            ]);
        }

        if ($sendCode != $code) {
            throw new SmsException("验证码错误", Code::invalid_sms_verify_code, [
                'send_code'  => $sendCode,
                'input_code' => $code
            ]);
        }

        return true;
    }
}