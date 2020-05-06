<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\Models\MchBankCardModel;
use Illuminate\Database\Eloquent\Model;

class  MchBanckCardRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '银行卡');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():MchBankCardModel
    {
        return $this->newSingle(MchBankCardModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function mchId()
    {
        return $this->row->mch_id;
    }

}