<?php

namespace App\Service\Listener;


use Abstracts\ListenerInterface;
use App\Exceptions\JobException;
use App\Http\Codes\Code;
use App\Models\JobSingleModel;
use App\DataTypes\JobTypes;

class MaterialUpdateListener implements ListenerInterface
{

    private $model;

    private $type;

    public function __construct($type)
    {
        $this->type  = $type;
        $this->model = new JobSingleModel();

        $this->check();

        $this->newJob();
    }

    private function name()
    {
        return JobTypes::name_material_update . '_' . $this->type;
    }

    private function newJob()
    {
        $this->model->create(['name' => $this->name()]);
    }

    private function check()
    {
        if($this->model->getDoingJob($this->name())){
            throw new JobException("上一个素材更新任务执行中...", Code::fail);
        }
    }

    public function change($data)
    {
        $this->model->jobSuccess($this->name());
    }
}