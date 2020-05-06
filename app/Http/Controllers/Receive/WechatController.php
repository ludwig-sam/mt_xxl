<?php namespace App\Http\Controllers\Receive;

use App\Service\Reply\Receive;
use App\Service\Wechat\Auth;
use App\Service\Wechat\Wechat;
use Illuminate\Http\Request;


class WechatController extends BaseController {

    private function wechatResponse($message)
    {
        return Receive::responseOriginalMsg($message);
    }

    private function exception(\Exception $exception, $root)
    {
        Receive::exception($exception, $root);
    }

    public function serve()
    {
        try{
            $wechatApp = (new Wechat())->serve();

            $wechatApp->server->push(function ($message){
                return $this->wechatResponse($message);
            });

            return $wechatApp->server->serve();
        }catch (\Exception $exception){
            $this->exception($exception, 'wechat');
        }
    }

    public function customServe(Request $request)
    {
        try{
            return $this->wechatResponse($request->all());
        }catch (\Exception $exception){
            $this->exception($exception, 'custom');
        }
    }

    public function auth()
    {
        $auth_service = new Auth();

        return redirect()->to($auth_service->callback());
    }

}