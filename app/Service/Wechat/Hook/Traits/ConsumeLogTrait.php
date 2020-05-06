<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/25
 * Time: 上午11:19
 */

namespace App\Service\Wechat\Hook\Traits;


use App\Models\CardModel;
use App\Models\ExeModel;
use App\Models\ExeOpratorModel;
use App\Models\PayCardConsumeLog;


trait ConsumeLogTrait
{
    private function getCardInfo($card_id)
    {
        $card_model = new CardModel();
        $info = $card_model->find($card_id);

        return $info;
    }

    private function getCardField($card_id, $field)
    {
        $info = $this->getCardInfo($card_id);

        if(!$info){
            return null;
        }

        return $info->$field;
    }


    private function getOpratorInfo()
    {
        $oprator_model = new ExeOpratorModel();
        $info = $oprator_model->find($this->opratorId());

        return $info;
    }

    private function getOpratorField($field)
    {
        $info = $this->getOpratorInfo();

        if(!$info){
            return null;
        }

        return $info->$field;
    }

    private function getExeInfo()
    {
        $exe_model = new ExeModel();
        $info = $exe_model->find($this->exeId());

        return $info;
    }

    private function getExeField($field)
    {
        $info = $this->getExeInfo();

        if(!$info){
            return null;
        }

        return $info->$field;
    }

    public function saveLog($card_id, $code, $out_str)
    {
        $card_model = new PayCardConsumeLog();

        $data = [
            'order_no'    => $this->orderNo(),
            'card_id'     => $this->getCardField($card_id, 'id'),
            'wx_card_id'  => $this->getCardField($card_id, 'card_id'),
            'card_title'  => $this->getCardField($card_id, 'title'),
            'code_no'     => $code,
            'store_id'    => $this->storeId(),
            'exe_oprator_user_name' => $this->getOpratorField('username'),
            'exe_dev_no'  => $this->getExeField('dev_no'),
            'member_id'   => $this->memberId(),
            'out_str'     => $out_str
        ];

        $card_model->create($data);
    }

    abstract function storeId();
    abstract function opratorId();
    abstract function exeId();
    abstract function memberId();
    abstract function orderNo();
}