<?php namespace App\Service\Pay;

use App\DataTypes\PayOrderStatus;
use App\Models\PayOrderModel;
use App\PayConfig;
use Illuminate\Support\Collection;
use Providers\Curd\CurdServiceTrait;
use App\Service\Service;

class Bill extends Service
{

    use CurdServiceTrait;

    public function model():PayOrderModel
    {
        return $this->newSingle(PayOrderModel::class);
    }

    public function inTotal()
    {
        return $this->model()->total(PayOrderStatus::PAY_STATUS_SUCCES);
    }

    public function inTotalBelongMch()
    {
        return $this->model()->totalBelongMch(PayOrderStatus::PAY_STATUS_SUCCES);
    }

    public function inTotalBelongTheMch($mch_id)
    {
        return $this->model()->totalBelongTheMch(PayOrderStatus::PAY_STATUS_SUCCES, $mch_id);
    }

    public function totalBalancePay($request)
    {
        $request = new Collection($request);
        return $this->model()->totalWithPayment(PayConfig::PAYMENT_BALANCE, $request);
    }

    public function totalToday($mch_id)
    {
        return $this->model()->totalToday($mch_id);
    }

    public function countNumToday($mch_id)
    {
        return $this->model()->countNumToday($mch_id);
    }
}

