<?php

namespace App\Service\Template;



use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\Code;
use App\Models\MaterialModel;
use App\DataTypes\MaterialTypes;
use App\Models\MessageSendLogModel;
use App\Service\Material\Factory;
use App\Service\MessageSend\Helper\TemplateHelper;
use App\Service\Service;
use Illuminate\Support\Collection;


class TemplateRow extends Service
{

    private $row;

    function __construct($id)
    {
        $factory = Factory::make(MaterialTypes::template);

        $this->row = $factory->get($id);

        $this->check();
    }

    function check()
    {
        if(!$this->row){
            throw new ExceptionCustomCodeAble('模版不存在', Code::not_exists);
        }
    }

    function getRow($id)
    {
        $model = new MaterialModel();
        return $model->find($id);
    }

    function templateId()
    {
        return $this->row->template_id;
    }

    function param()
    {
        return $this->row->param;
    }

    function data()
    {
        $parma = new Collection($this->param());

        return $parma->get('data', []);
    }

    function url()
    {
        $parma = new Collection($this->param());

        return $parma->get('url', '');
    }

    function getSendParsePlaceholder(array $message)
    {
        $original_data  = $this->data();

        list($template_data, $template_url)  = TemplateHelper::replacePlaceholder($original_data, $this->url(), $message);

        $template_data  = TemplateHelper::toData($template_data);

        $template_param = $this->param();
        $template_param['data'] = $template_data;
        $template_param['url']  = $template_url;

        return $template_param;
    }
}