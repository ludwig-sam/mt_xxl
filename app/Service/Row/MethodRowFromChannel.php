<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/14
 * Time: 上午11:50
 */

namespace App\Service\Row;


class  MethodRowFromChannel extends MethodRow
{

    protected $row;

    public function __construct($channel)
    {
        $this->row = $this->model()->getByKey($channel);

        $this->check($this->row, '支付方式:' . $channel);
    }
}