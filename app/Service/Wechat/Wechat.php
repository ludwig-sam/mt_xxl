<?php namespace App\Service\Wechat;



use Libs\Log;
use App\Providers\EasyWechatService;
use Providers\ApiResultable;
use Providers\WechatApiResult;

class Wechat  {

    use ApiResultable;

    public function newResult($ret)
    {
        return new WechatApiResult($ret);
    }

    public function serve($param = []){

        $conf = config('wechat.official_account.default');
        $conf = array_merge($conf, $param);

        $wechatApp = \EasyWeChat\Factory::officialAccount($conf);

        $wechatApp->register(new EasyWechatService());

        return $wechatApp;
    }

    public function miniServe(){

        $wechatApp = \EasyWeChat\Factory::miniProgram(config('wechat.mini_program.default'));

        $wechatApp->register(new EasyWechatService());

        return $wechatApp;
    }

    protected function catch($fun){
        try{
            return $fun();
        }catch (\Exception $exception){
            Log::error("微信服务出错", [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'msg'  => addslashes($exception->getMessage())
            ]);
            return $this->parseResult('');
        }
    }


}