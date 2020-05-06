<?php namespace App\Http\Controllers\Admin;

use App\Exceptions\ParamException;
use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Response;
use App\Models\WechatMenuModel;
use App\Service\Wechat\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WechatMenuController extends BaseController {


    public function rule()
    {
        return  new Rules\WechatMenuRule();
    }

    public function add(ApiVerifyRequest $request)
    {
        $wechat_menu_service = new Menu();

        if(!$row = $wechat_menu_service->add(new Collection($request))){
            return Response::error(Code::create_fial, '添加失败');
        }

        return Response::success('添加成功', [
            'key' => $row->key
        ]);
    }

    public function update($id, Request $request)
    {
        $wechat_menu_service = new Menu();

        $old_row = $wechat_menu_service->model()->getRow($id);

        if(!$old_row){
            return Response::error(Code::not_exists, '菜单不存在');
        }

        if(!$wechat_menu_service->update($old_row, new Collection($request))){
            return Response::error(Code::create_fial, '修改失败');
        }

	    self::note("更新微信菜单", "菜单ID:{$id}");

        return Response::success('修改成功', [
            'key' => $old_row->key
        ]);
    }

    public function list()
    {
        $wechat_menu_service = new Menu();

        $list    = $wechat_menu_service->list();

        $wx_menu = $wechat_menu_service->getFromWx();

        return Response::success('', [
            'list'    => $list,
            'wx_menu' => $wx_menu
        ]);
    }

    public function refresh($condition_id)
    {
        $wechat_menu_service = new Menu();

        if(!$wechat_menu_service->refresh($condition_id)){
            return Response::error(Code::wechat_error, $wechat_menu_service->result()->getMsg());
        }

        return Response::success();
    }

    public function sort($id, ApiVerifyRequest $request)
    {
        $wechat_menu_model = new WechatMenuModel();

        $row = $wechat_menu_model->find($id);

        if(!$row){
            throw new ParamException("菜单不存在", Code::not_exists);
        }

        $row->update(['sort' => $request->get('sort')]);

        return Response::success("排序成功");
    }

    public function delete($id)
    {
        $wechat_menu_model = new WechatMenuModel();

        $row = $wechat_menu_model->find($id);

        if(!$row){
            throw new ParamException("菜单不存在", Code::not_exists);
        }

        if($wechat_menu_model->where('pid', $row->id)->first()){
            return Response::error(Code::fail, '请选删除子菜单');
        }

        $row->delete();

        return Response::success("删除成功");
    }

}