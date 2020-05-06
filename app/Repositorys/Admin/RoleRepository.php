<?php namespace App\Repositorys\Admin;


use App\Models\RbacRoleModel;
use App\Models\RbacRoleNodeModel;
use App\Models\RbacNodeModel;
use Libs\Tree;
use Libs\Arr;
use Illuminate\Support\Facades\DB;

class RoleRepository {

    private $model;

    public function __construct(RbacRoleModel $model)
    {
        $this->model = $model;
    }

    public function list($limit,$request)
    {
        return DB::table('rbac_role')
            ->leftJoin('rbac_user_role','rbac_role.id','=','rbac_user_role.role_id')
            ->select('rbac_role.id','rbac_role.name','rbac_role.status',DB::raw('COUNT(rbac_user_role.role_id) as Operator_count'))
            ->when($request['status'], function($query) use ($request){
               return $query->where('rbac_role.status', $request['status']);
             })
            ->when($request['name'], function($query) use ($request){
               return $query->where('rbac_role.name', $request['name']);
            })
            ->groupBy('rbac_role.id')
            ->orderBy('rbac_role.id','desc')
            ->paginate($limit);
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function  show($id)
    {
        $data = $this->model->find($id);
        $p=RbacRoleNodeModel::select('node_id')->where('role_id',$id)->get();

        $permissions = [];

        foreach ($p as $t)
        {
            array_push($permissions,$t->node_id);
        }

        $data['permissions'] = $permissions;
        return $data;

    }

    public function save($data)
    {
        $permissions=[];

        if(isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

        $this->model->fill($data);
        $this->model->save();

        if($permissions) {

            $permissions = array_unique($permissions);

            $adminList = RbacNodeModel::select('name','id','pid','action','module')->get()->toArray();

            Tree::path($adminList,0, [], 'action');
            $rbacRoleNodeModel = new RbacRoleNodeModel();
            foreach ($permissions as $node) {
                $path = [];
                array_push($path, Arr::find($adminList,$node)['module']);
                foreach (Arr::find($adminList,$node)['path'] as $p)
                {
                    array_push($path,$p);
                }
                $data=[
                    'role_id'=>$this->model->id,
                    'node_id'=>intval($node),
                    'node_path'=>join('.', $path)
                ];
                $rbacRoleNodeModel->create($data);
            }
        }
        return true;
    }

    public function chStatus($data)
    {
        return $this->model->find($data['id'])->fill($data)->save();
    }

    public function update($data)
    {
        $permissions=[];
        $str=['id','name','remark','status','permissions'];
        $arr =Arr::getIfExists($data,$str);

        RbacRoleNodeModel::where('role_id',$arr['id'])->delete();

        if(isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $role = $this->model->find($arr['id']);

        if($permissions)
        {
            $permissions = array_unique($permissions);

            $adminList = RbacNodeModel::select('name','id','pid','action','module')->get()->toArray();
            Tree::path($adminList,0, [], 'action');

            $rbacRoleNodeModel = new RbacRoleNodeModel();
            $rbacRoleNodeModel->where('role_id',$role->id)->delete();

            foreach ($permissions as $node) {
                $path = [];
                array_push($path, Arr::find($adminList,$node)['module']);
                foreach (Arr::find($adminList,$node)['path'] as $p)
                {
                    array_push($path,$p);
                }
                $data=[
                    'role_id'=>$role->id,
                    'node_id'=>intval($node),
                    'node_path'=>join('.', $path)
                ];
                $rbacRoleNodeModel->create($data);
            }
        }

        return $role->fill($arr)->save();
    }

    public function permissionsList($model)
    {
        $rbac_node_model = new RbacNodeModel();
        $list            = $rbac_node_model->where('module', $model)->where('is_use', 1)->select('id', 'pid', 'name')->get();
        return Tree::layer($list);
    }

    public function delete($id)
    {
        if(DB::table('rbac_user_role')->where('role_id',$id)->first())
        {
            return false;
        }
        DB::table('rbac_role_node')->where('role_id',$id)->delete();
        $this->model->find($id)->delete();
        return $id;
    }

}