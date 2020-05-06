<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/25
 * Time: 上午11:19
 */

namespace App\Service\Wechat\Hook\Traits;


use Abstracts\ListenerInterface;
use App\Models\MessageSendConfigModel;
use App\Models\PayNotifyUserModel;
use App\Service\MessageSend\Helper\TemplateHelper;
use App\Service\MessageSend\MessageService;
use App\Service\Users\MemberUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


trait OrderChangeNotifyTrait
{

    private function getUser()
    {
        $user = MemberUser::getInstance();
        $user->setId($this->message->getAttr('member_id'));

        return $user;
    }

    private function getUsers()
    {
        if(method_exists($this, 'specialGetUsers')){
            return $this->specialGetUsers();
        }

        $fans_model = new PayNotifyUserModel();

        return $fans_model->getPayNotifyOpenids($this->mchId());
    }

    private function getTemplate()
    {
        $message_send_config_model = new MessageSendConfigModel();
        return $message_send_config_model->where('name', $this->templateName())->first();
    }

    private function send(Model $row)
    {

        $order_info     = $this->message->toArray();

        $template_param = new Collection($row->param);

        $original_data  = $template_param->get('data');
        $template_url   = $template_param->get('url', '');

        list($template_data, $template_url)  = TemplateHelper::replacePlaceholder($original_data, $template_url, $order_info);

        $template_data  = TemplateHelper::toData($template_data);

        $template_param->offsetSet('data', $template_data);
        $template_param->offsetSet('url', $template_url);


        $MessageService = new MessageService($this->getUser());

        $MessageService->init($row->method, 'openid', 'General');

        $MessageService->sendByAsync($this->getUsers(), $template_param->toArray(), $this->listener());
    }

    private function mchId()
    {
        return $this->message->getAttr('mch_id');
    }

    abstract function templateName();

    abstract function listener():ListenerInterface;
}