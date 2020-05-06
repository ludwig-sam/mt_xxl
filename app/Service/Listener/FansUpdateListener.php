<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/12
 * Time: 上午11:04
 */

namespace App\Service\Listener;


use Abstracts\ListenerInterface;
use App\Exceptions\JobException;
use App\Http\Codes\Code;
use App\Models\JobSingleModel;
use App\DataTypes\JobTypes;
use App\Service\Fans\CreateCache;
use App\Service\Fans\UpdateCache;

class FansUpdateListener implements ListenerInterface
{

    private $model;

    public function __construct()
    {
        $this->model = new JobSingleModel();

        $this->check();

        $this->newJob();
    }

    private function newJob()
    {
        $this->model->create(['name' => JobTypes::name_fans_update]);
    }

    private function check()
    {
        if($this->model->getDoingJob(JobTypes::name_fans_update)){
            throw new JobException("上一个粉丝更新任务执行中...", Code::fail);
        }
    }

    public function change($data)
    {
        CreateCache::cache()->flush();
        UpdateCache::cache()->flush();

        $this->model->jobSuccess(JobTypes::name_fans_update);
    }
}