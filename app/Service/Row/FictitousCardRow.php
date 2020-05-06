<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\Models\FictitiousCardModel;
use App\DataTypes\FictitiousCardTypes;
use Illuminate\Database\Eloquent\Model;

class  FictitousCardRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '充值卡');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():FictitiousCardModel
    {
        return $this->newSingle(FictitiousCardModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function status()
    {
        return $this->row->status;
    }

    public function isNormal()
    {
        return $this->status() == FictitiousCardTypes::status_normal;
    }

    public function isDisabled()
    {
        return $this->status() == FictitiousCardTypes::status_disabled;
    }

    public function dateType()
    {
        return $this->row->date_type;
    }

    public function isPermanent()
    {
        return $this->row->date_type == FictitiousCardTypes::date_type_permanent;
    }

    public function startTime()
    {
        return strtotime($this->startAt());
    }

    public function endTime()
    {
        return strtotime($this->endAt());
    }

    public function startAt()
    {
        return $this->row->start_at;
    }

    public function endAt()
    {
        return $this->row->end_at;
    }

    public function amount()
    {
        return $this->row->amount;
    }

    public function stock()
    {
        return $this->row->stock;
    }

    public function quantity()
    {
        return $this->row->quantity;
    }
}