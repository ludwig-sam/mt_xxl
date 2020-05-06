<?php namespace App\Http\Controllers\Receive;



use Libs\Response;
use App\Service\Wechat\Wechat;

class MtAccessTokenController extends BaseController
{


    public function get()
    {
        $wechat_service = new Wechat();

        $access_token   = $wechat_service->serve()->access_token->getToken();

        return Response::success('', [
            'token' => $access_token
        ]);
    }

}