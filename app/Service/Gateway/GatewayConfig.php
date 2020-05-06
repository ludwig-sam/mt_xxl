<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway;



use App\Models\GatewayConfigModel;

class GatewayConfig extends \App\Service\Gateway\Contracts\GatewayConfigAbstricts
{

    private $config = [];
    private $model;


    private function model()
    {
        if(!$this->model){
            $this->model = new GatewayConfigModel();
        }

        return $this->model;
    }

    public function reloadConfig()
    {
        $list = $this->model()->get();

        foreach ($list as $row){
            $this->config[$row->name] = $row->value;
        }
    }

    public function config()
    {
        return $this->config ? : [];
    }

    public function ipBlackList($value)
    {
        return json_decode($value, true);
    }

    public function set($field, $value)
    {
        $value = is_string($value) ? $value : json_encode($value);

        $row   = $this->model()->where('name', $field)->first();

        $data  = [
            'name'  => $field,
            'value' => $value
        ];

        if($row){
            $ret = $row->update($data);
        }else{
            $ret = $this->model()->create($data);
        }

        $this->toCache();

        return  $ret;
    }

    private function toCache()
    {
        $this->reloadConfig();

        $cache = new GatewayConfigCache();

        foreach ($this->config as $field => $value){
            $cache->set($field, $value);
        }
    }

}