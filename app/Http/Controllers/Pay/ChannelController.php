<?php namespace App\Http\Controllers\Pay;


use App\Http\Rules\Pay\PayRule;
use Libs\Response;
use App\Models\PayMethodModel;

class ChannelController extends BaseController
{


    public function rule()
    {
        return new PayRule();
    }

    public function channelsList()
    {
        $payMethod = new PayMethodModel();
        $data      = $payMethod->channelsList();
        return Response::success('', ['list' => $data->toArray()]);
    }
}