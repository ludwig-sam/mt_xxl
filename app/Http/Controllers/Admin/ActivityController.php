<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 2018/8/22
 * Time: 16:10
 */

namespace App\Http\Controllers\Admin;


use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Admin\Activity;
use Libs\Arr;
use Libs\Response;
use App\Models\ActivityModel;

class ActivityController extends BaseController
{

    function rule()
    {
        return new Activity();
    }

    public function create(ApiVerifyRequest $request, ActivityModel $model)
    {
        $data = Arr::getIfExists($request->all(), ["name", " start_at", "end_at", "pic", "detail", "sort"]);
        if (!$model->create($data)) {
            return Response::error(Code::create_fial, '添加失败');
        }
        return Response::success('添加成功');
    }

    public function activityList(ActivityModel $model)
    {
        return Response::success('', $model->activatyLimit($this->limitNum()));
    }

    public function show($id, ActivityModel $model)
    {
        if (!$data = $model->find(intval($id))) {
            return Response::error(Code::not_exists, "该活动不存在");
        }
        return Response::success('', $data);
    }

    public function update(ApiVerifyRequest $request, ActivityModel $model)
    {
        if (!$data = $model->find($request->get('id'))) {
            return Response::error(Code::not_exists, "该活动不存在");
        }

        $request = Arr::getIfExists($request->all(), ["name", " start_at", "end_at", "pic", "detail", "sort"]);

        if (!$data->update($request)) {
            return Response::error(Code::update_fail, "更新失败");
        }
        return Response::success('更新成功');
    }

    public function delete($id, ActivityModel $model)
    {
        if (!$data = $model->find(intval($id))) {
            return Response::error(Code::not_exists, "该活动不存在");
        }

        if (!$data->delete()) {
            return Response::error(Code::delete_fail, "删除失败");
        }

        return Response::success('删除成功');
    }
}