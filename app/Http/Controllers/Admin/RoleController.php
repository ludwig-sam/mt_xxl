<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Response;
use App\Http\Codes\LeiCode;
use App\Repositorys\Admin\RoleRepository;

class RoleController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Admin\Role();
    }

    public function __construct(RoleRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function roleLists(ApiVerifyRequest $request)
    {
        return Response::success('',$this->repository->list($this->limitNum(),$request));
    }

    public function show($id)
    {
        if(!$data=$this->repository->show($id)){
            return Response::error(LeiCode::not_exists, '角色不存在');
        }
        return Response::success('',$data);
    }

    public function delete($id)
    {
        if(!$this->repository->find($id)){
            return Response::error(LeiCode::not_exists, '角色不存在无法删除');
        }
        $data=$this->repository->delete($id);
        if($data===false)
        {
            return Response::error(LeiCode::being_used, '删除失败 该角色正在被使用');
        }
	    $detial = "删除了角色ID:".$id;
	    self::note("删除角色:",$detial);
        return Response::success('删除成功',['id'=>$data]);
    }

    public function chStatus(ApiVerifyRequest $request)
    {
        if(!$this->repository->find($request['id'])){
            return Response::error(LeiCode::not_exists, '角色不存在无法修改状态');
        }
        $data=$this->repository->chStatus($request->all());

        $detial = "更新了角色状态:id".$request['id'];
        self::note("更新角色状态:",$detial);
        return Response::success('修改成功',$data);
    }

    public function add(ApiVerifyRequest $request)
    {
        if(!$this->repository->save($request->all())){
            return Response::error(LeiCode::role_add_fail, '网络错误');
        }
        return Response::success('添加成功');
    }

    public function update(ApiVerifyRequest $request)
    {
        if(!$this->repository->find($request['id'])){
            return Response::error(LeiCode::not_exists, '角色不存在无法修改');
        }
        $data=$this->repository->update($request->all());

	    $detial = "更新了角色:id".$request['id'];
	    self::note("更新角色:",$detial);
        return Response::success('修改成功',$data);
    }

    public function permissionsList()
    {
        return Response::success('', [
            'admin' => $this->repository->permissionsList('admin'),
            'mchs'  => $this->repository->permissionsList('mchs'),
        ]);
    }
}