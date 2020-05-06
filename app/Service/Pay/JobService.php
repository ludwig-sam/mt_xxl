<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/12
 * Time: 下午2:06
 */

namespace App\Service\Pay;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Service\Row\OrderRow;
use Libs\Route;
use App\Service\JobApi\HardWork;
use App\Service\JobApi\Model\MessageModel;

class JobService
{

    public function job(OrderRow $order, $max_execute = 50)
    {
        $model = new MessageModel();

        $model->topic          = 'autocall';
        $model->delay          = 10;
        $model->max_execute    = $max_execute;
        $model->next_delay     = 20;
        $model->request_url    = Route::named('order_status');
        $model->request_method = 'post';
        $model->callback       = Route::named('pay_notify', [$order->orderNo()]);

        $model->body = ['order_id' => $order->id()];

        $model->condition = ['type' => 'not_eque', 'key' => 'trade_status', 'val' => 'TRADE_WAITING_PAY'];

        $hard_work = new HardWork();

        if (!$hard_work->create($model)) {
            throw new ExceptionCustomCodeAble($hard_work->result()->getMsg());
        }

        return true;
    }
}