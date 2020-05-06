<?php namespace App\Http\Controllers\Admin;


use App\Exceptions\MessageSendException;
use App\Http\Codes\Code;
use App\Http\Rules\TemplateConfigRule;
use Libs\Arr;
use Libs\Response;
use App\DataTypes\MessagePlaceholders;
use App\Models\MessageSendConfigModel;
use App\DataTypes\MessageSendRoots;
use App\Service\Template\TemplateService;
use Illuminate\Http\Request;

class TemplateConfigController extends BaseController
{


    public function rule()
    {
        return new TemplateConfigRule();
    }

    public function tempalteInit(Request $request)
    {
        $name = $request->get('name');
        $template_id = $request->get('template_id');
        $template_service = new TemplateService();

//        MessageSendRoots::checkRoots($name, MessageSendRoots::getRoots());

        if(!$template_service->specialTemplateInitByTemplateId($template_id, $name)){
            return Response::error(Code::fail, "设置失败");
        }

        return Response::success('设置成功');
    }

    public function templateList()
    {
        $template_model = new MessageSendConfigModel();

        return Response::success('', $template_model->getTemplateList());
    }

    public function templateGet($id)
    {
        $template_service = new TemplateService();

        $row = $template_service->getRowAndCheck($id);

        return Response::success('', ['remark' => $row->remark, 'name' => $row->name, 'method' => $row->method, 'template_id' => $row->template_id, 'miniprogram_pagepath' => $row->miniprogram_pagepath, 'url' => $row->url, 'data' => array_get($row->param, 'data', []), 'placeholder' => MessagePlaceholders::getPlaceholder($row->name)]);
    }

    public function templateUpdate($id, Request $request)
    {
        $param = [];
        $data = $request->get('data');

        $template_service = new TemplateService();

        $save_data = Arr::getIfExists($request->all(), ['remark', 'url', 'miniprogram_pagepath',]);

        $template_service->addMiniProgram($param, $save_data);

        $template_service->addUrl($param, $save_data);

        $old_row = $template_service->getRowAndCheck($id);

        $param = $template_service->getNewParam($old_row, $data);

        $save_data['param'] = $param;

        if(!$old_row->update($save_data)){
            return Response::error(Code::update_fail, "更新失败");
        }

        return Response::success("更新成功");
    }


}