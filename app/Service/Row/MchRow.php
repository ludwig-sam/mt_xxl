<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


use App\Models\MchModel;
use Illuminate\Database\Eloquent\Model;

class  MchRow extends BaseRow
{

    protected $row;

    public function __construct($id)
    {
        $this->row = $this->getAndCheck($id, '商户');
    }

    function getRow():Model
    {
        return $this->row;
    }

    public function model():MchModel
    {
        return $this->newSingle(MchModel::class);
    }

    public function id()
    {
        return $this->row->id;
    }

    public function name()
    {
        return $this->row->name;
    }

}