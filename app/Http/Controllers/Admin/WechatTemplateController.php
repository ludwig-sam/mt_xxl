<?php namespace App\Http\Controllers\Admin;



use Libs\Response;
use App\Service\MessageSend\Helper\TemplateHelper;
use App\Service\Wechat\Template;

class WechatTemplateController extends BaseController {


    public function rule()
    {
    }

    public function __construct()
    {
        parent::notNeedPermission();
        parent::__construct();
    }

    public function list()
    {
        $list = [];
        $wechat_template_service = new Template();
        $wx_templates = $wechat_template_service->getList();

        foreach ($wx_templates as $wx_template){
            $list[] = [
                'template_id' => $wx_template['template_id'],
                'title'       => $wx_template['title'],
                'data'        => TemplateHelper::parse($wx_template['content'])
            ];
        }

        return Response::success('', $list);
    }

}