<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\Models\MchModel;
use App\Models\MchWithdrawModel;
use Illuminate\Database\Eloquent\Model;

class  MchWithdrawRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '提现申请');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():MchWithdrawModel
    {
        return $this->newSingle(MchWithdrawModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function status()
    {
        return $this->row->status;
    }
}