<?php

namespace App\Service\Template;


use App\Exceptions\MessageSendException;
use App\Http\Codes\Code;
use Libs\Arr;
use App\DataTypes\MessagePlaceholders;
use App\Models\MessageSendConfigModel;
use App\DataTypes\MessageSendTypes;
use App\Service\MessageSend\Helper\TemplateHelper;
use App\Service\Service;
use App\Service\Wechat\Template;
use Illuminate\Support\Str;

class TemplateService extends Service
{


    function templateInitByTemplateId($template_id)
    {
        $old_row  = $this->model()->where('template_id', $template_id)->first();

        if($old_row)return true;

        $template = $this->getWxTemplate($template_id);

        $data     = TemplateHelper::parse($template['content']);

        $save = [
            'method'      => MessageSendTypes::type_template,
            'template_id' => $template_id,
            "remark"      => $template['title'],
            'param'        => [
                'template_id' => $template_id,
                'data'        => $data
            ]
        ];

        return $this->model()->create($save);
    }

    function specialTemplateInitByTemplateId($template_id, $name)
    {
        $row = $this->getRowByName($name);

        if($this->ifInited($row, $template_id)){
            return true;
        }

        $template = $this->getWxTemplate($template_id);

        $data     = TemplateHelper::parse($template['content']);

        $data     = $this->preDefind($name, $data);

        $save = [
            'method'      => MessageSendTypes::type_template,
            'template_id' => $template_id,
            'param'        => [
                'template_id' => $template_id,
                'data'        => $data
            ]
        ];

        return $row->update($save);
    }

    private function ifInited($row, $template_id)
    {
        return $row->template_id == $template_id;
    }

    private function getRowByName($name)
    {
        $row = $this->model()->where([
            'name'        => $name
        ])->first();

        if(!$row){

            $row = $this->model()->create(['name' => $name]);

            if(!$row) throw new MessageSendException($name . '模板初始化失败', Code::create_fial);
        }

        return $row;
    }

    public function getRow($id)
    {
        return $this->model()->find($id);
    }

    public function model()
    {
        static  $model;

        if($model){
            return $model;
        }

        $model = new MessageSendConfigModel();

        return $model;
    }

    public function getRowAndCheck($id)
    {
        $old_row  = $this->model()->find($id);

        if(!$old_row){
            throw new MessageSendException("模板不存在", Code::not_exists);
        }

        return $old_row;
    }

    public function getNewParam($old_row, $data)
    {
        $old_param = $old_row->param;

        if(!$data){
            return $old_param;
        }

        $old_data = array_get($old_param, 'data', []);

        if(count($data) != count($old_data)){
            throw new MessageSendException("param参数结构不能变", Code::invalid_param);
        }

        $old_param['data'] = $data;

        return $old_param;
    }

    public function getWxTemplate($template_id)
    {
        $wx_template_service = new Template();

        $list                = $wx_template_service->getList();

        if(!$template = Arr::find($list, $template_id, 'template_id')){
            throw new MessageSendException('请输入正确的模板ID:' . $template_id, Code::not_exists);
        }

        return $template;
    }

    private function preDefind($name, $data)
    {
        $specialInitMethod = Str::studly($name) . 'SpecialInit';

        return method_exists($this, $specialInitMethod) ? $this->$specialInitMethod($data) : $data;
    }

    public function addMiniProgram(&$param_data, $save)
    {
        $miniprogram_pagepath = array_get($save, 'miniprogram_pagepath');

        if(!$miniprogram_pagepath){
            array_forget($param_data, 'miniprogram');

            return ;
        }

        $param_data['miniprogram'] = [
            'appid'     => config('wechat.mini_program.default.app_id'),
            'pagepath'  => $miniprogram_pagepath
        ];
    }

    public function addUrl(&$param_data, $save)
    {
        $url = array_get($save, 'url');

        $param_data['url'] = $url;
    }

    function payNotifySpecialInit($data)
    {

        foreach ($data as &$datum){

            $value = $datum['value'];

            switch ($datum['name']){
                case 'first':
                        $value = '有一笔订单支付成功';
                    break;
                case 'keyword1':
                        $value = TemplateHelper::getCurePlaceholder(MessagePlaceholders::pay_notify_order_no);
                    break;
                case 'keyword2':
                        $value = TemplateHelper::getCurePlaceholder(MessagePlaceholders::pay_notify_payment_at);
                    break;
                case 'keyword3':
                        $value = TemplateHelper::getCurePlaceholder(MessagePlaceholders::pay_notify_amount);
                    break;
                case 'remark':
                        $value = '如有问题，请到后台查看。';
                    break;
            }

            $datum['value'] = $value;
        }

        return $data;
    }

    function refundNotifySpecialInit($data)
    {

        foreach ($data as &$datum){

            $value = $datum['value'];

            switch ($datum['name']){
                case 'first':
                    $value = '有一笔订单退款成功';
                    break;
                case 'reason':
                    $value = TemplateHelper::getCurePlaceholder(MessagePlaceholders::refund_notify_refund_reason);
                    break;
                case 'refund':
                    $value = TemplateHelper::getCurePlaceholder(MessagePlaceholders::refund_notify_refund_amount);
                    break;
                case 'remark':
                    $value = '如有问题，请到后台查看。';
                    break;
            }

            $datum['value'] = $value;
        }

        return $data;
    }
}