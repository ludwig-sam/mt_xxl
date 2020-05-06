<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\DataTypes\PayMethodStatus;
use App\Models\PayMethodModel;
use App\PayConfig;
use Illuminate\Database\Eloquent\Model;

class  MethodRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '支付方式');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():PayMethodModel
    {
        return $this->newSingle(PayMethodModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function uCWay()
    {
        return $this->row->way;
    }

    public function config()
    {
        return $this->row->config;
    }

    public function name()
    {
        return $this->row->name;
    }

    public function channel()
    {
        return $this->row->channel;
    }

    public function tradeType()
    {
        return $this->row->trade_type;
    }

    public function status()
    {
        return $this->row->status;
    }

    public function isDisabled()
    {
        return $this->status() == PayMethodStatus::disabled;
    }

    public function isAsync()
    {
        return $this->row->is_async == 1;
    }

    public function isNeedPwd()
    {
        return $this->row->is_need_pwd;
    }

    public function isBalance()
    {
        return $this->id() == PayConfig::PAYMENT_BALANCE;
    }

}