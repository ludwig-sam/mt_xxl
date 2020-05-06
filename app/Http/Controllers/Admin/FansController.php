<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\FansRule;
use App\Jobs\ProcessFansCreate;
use Libs\Response;
use App\Models\FansModel;
use App\Service\Listener\FansUpdateListener;
use App\Service\Fans\Updating;
use Illuminate\Support\Collection;

class FansController extends BaseController {


    public function rule()
    {
        return new FansRule();
    }


    public function update()
    {
        $fans_service = new Updating();

        //1
        $listener     = new FansUpdateListener();

        //2
        $fans_service->pull();

        $this->dispatch(new ProcessFansCreate($listener));

        return Response::success("任务提交成功");
    }

    public function updateByOpenid(ApiVerifyRequest $request)
    {
        $fans_service = new Updating();
        $openid       = $request->get("openid");

        if($fans_service->updateByOpenid($openid)){
            return Response::success("更新成功");
        }

        return Response::error(Code::fail, "更新失败");
    }

    public function list(ApiVerifyRequest $request)
    {
        $fans_model = new FansModel();

        $list = $fans_model->getLimit($this->limitNum(), new Collection($request->all()));

        return Response::success('', $list);
    }
}