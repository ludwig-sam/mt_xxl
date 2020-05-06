<?php namespace App\Http\Controllers\Admin;

use App\Http\Rules;
use Libs\Arr;
use Libs\Response;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Codes\Code;
use App\Service\Oprator\AdminOprator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OpratorController extends BaseController {


    public function rule()
    {
        return new Rules\Admin\OpratorRule();
    }

    private function service()
    {
        static $service;

        if(!$service){
            $service = new AdminOprator();
        }

        return $service;
    }

    public function add(ApiVerifyRequest $request)
    {
        $req_coll = new Collection($request->all());

        $this->service()->check($req_coll);

        $roles = $this->service()->checkRoleAndGet($req_coll);

        if(!$user_id = $this->service()->create($req_coll->all())){
            return Response::error(Code::create_user_fail, '添加失败');
        }

        $this->service()->saveRole($req_coll, $user_id, $roles);

        return Response::success('添加成功');
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $req_coll  = new Collection($request->all());

        $admin_row = $this->service()->getRow($id);

        $this->service()->check($req_coll);

        $roles = $this->service()->checkRoleAndGet($req_coll);

        $data = Arr::getIfExists($req_coll->all(), ['password', 'is_super', 'mch_id', 'status', 'mobile', 'headurl', 'person_name']);

        if(!$this->service()->update($admin_row, $data)){
            return Response::error(Code::create_user_fail, '更新失败');
        }

        $this->service()->saveRole($req_coll, $id, $roles);

        self::note('更新操作员', "操作员名称:" . $admin_row->user_name);

        return Response::success('更新成功');
    }

    public function list(Request $request)
    {
        $list = $this->service()->getLimit($this->limitNum(), new Collection($request));

        return Response::success('', $list);
    }

    public function get($id)
    {
        $admin_row = $this->service()->getRow($id);

        $roles = $this->service()->getRoles($id);

        return Response::success('', [
            'info'  => $admin_row,
            'roles' => $roles
        ]);

    }

    public function delete($id)
    {
        $admin_row = $this->service()->getRow($id);

        if(!$this->service()->delete($id)) {
            return Response::error(Code::fail, "删除失败");
        }

        self::note("删除操作员", $admin_row->user_name);

        return Response::success("删除成功");
    }

    public function chStatus($id, ApiVerifyRequest $request)
    {
        $admin_row = $this->service()->getRow($id);
        $status    = $request->get('status');

        $this->service()->update($admin_row, [
            'status' => $status
        ]);

        self::note('更新了操作员', '将' . $admin_row->user_name . '的状态更新为:' . $status);

        return Response::success('修改成功');
    }

}