<?php namespace App\Http\Controllers\Mchs;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\PayNotifyRule;
use Libs\Response;
use App\Models\FansModel;
use App\Models\PayNotifyUserModel;
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
        $openid = $request->get('openid');

        $fans_model = new FansModel();
        $fans_row   = $fans_model->where('openid', $openid)->first();

        if(!$fans_row){
            return Response::error(Code::not_exists, '粉丝不存在');
        }

        $notify_user_model = new PayNotifyUserModel();

        if($notify_user_model->where("fans_id", $fans_row)->first()){
            return Response::success("设置成功");
        }

        $affetch = $notify_user_model->create([
            "fans_id" => $fans_row->id,
            "openid"  => $openid
        ]);

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
	    $detial = "删除收款通知人ID:".$id;
	    self::note('删除收款通知人',$detial);
        return Response::success("删除成功");
    }

    public function notifyUser(Request $request)
    {
        $fans_model = new PayNotifyUserModel();

        $list = $fans_model->getLimitWithFans($this->limitNum(), new Collection($request));

        return Response::success('', $list);
    }
}