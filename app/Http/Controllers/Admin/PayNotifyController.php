<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\PayNotifyRule;
use Libs\Response;
use App\Models\FansModel;
use App\Models\PayNotifyUserModel;
use App\Service\NotifyUser\NotifyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PayNotifyController extends BaseController
{

    public function rule()
    {
        return new PayNotifyRule();
    }

    public function addUser(ApiVerifyRequest $request)
    {
        $pay_notify_user = new NotifyUser();

        $affetch = $pay_notify_user->add($request->get('openid'), $request->get('mch_id'));

        if($affetch){
            return Response::success("设置成功");
        }

        return Response::error(Code::fail, "设置失败");
    }

    public function removeUser($id)
    {
        $fans_model = new PayNotifyUserModel();

        $row = $fans_model->find($id);

        if(!$row){
            return Response::error(Code::not_exists, "通知人不存在");
        }

        $row->delete();

	    self::note('删除收款通知人' , "删除收款通知人ID:{$id}");

        return Response::success("删除成功");
    }

    public function notifyUser(Request $request)
    {
        $fans_model = new PayNotifyUserModel();

        $list = $fans_model->getLimitWithFans($this->limitNum(), new Collection($request));

        return Response::success('', $list);
    }
}