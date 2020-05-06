<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/17
 * Time: 下午12:05
 */

namespace App\Service\Oprator;

use App\Http\Codes\Code;
use App\Models\AdminModel;
use App\Models\MchModel;
use App\Models\RbacNodeModel;
use App\Models\RbacRoleModel;
use App\Models\RbacUserRoleModel;
use Libs\Arr;
use App\Exceptions\AuthException;
use Illuminate\Support\Collection;

class AdminOprator
{

    public function getRequestRoleIds(Collection $collection)
    {
        $role_ids = (array)$collection->get('role_ids');
        $role_ids = Arr::filter($role_ids);
        $role_ids = array_unique($role_ids);

        return $role_ids;
    }

    public function getMchId(Collection $collection)
    {
        return $collection->get('mch_id');
    }

    public function isSuper(Collection $collection)
    {
        return $collection->get('is_super')  == 1;
    }

    public function checkRoleAndGet(Collection $req_coll)
    {
        $role_ids = $this->getRequestRoleIds($req_coll);
        $result   = [];

        if($role_ids){

            if(count($role_ids) > 6){
                throw new AuthException("一个操作员的角色不能超过6个");
            }

            $role_model = new RbacRoleModel();
            foreach ($role_ids as $role_id){
                if(!$role_row = $role_model->find($role_id)){
                    throw new AuthException("角色不存在：" . $role_id, Code::role_not_exists);
                }
                $result[] = [
                    'id'    => $role_id,
                    'name'  => $role_row->name
                ];
            }
        }

        return $result;
    }

    public function check(Collection $req_coll)
    {
        $mch_id   = $this->getMchId($req_coll);

        if($mch_id){
            $mch_model = new MchModel();

            if(!$mch_model->find($mch_id)){
                throw new AuthException("商户不存在：" . $mch_id, Code::mch_not_exists);
            }
        }
    }

    public function saveRole(Collection $req_coll, $user_id, $roles)
    {
        if($this->isSuper($req_coll))return ;

        $role_model = new RbacUserRoleModel();

        $role_model->where('user_id', $user_id)->delete();

        foreach($roles as $role){
            $role_model->create([
                'user_id'   => $user_id,
                'role_id'   => $role['id'],
                'role_name' => $role['name']
            ]);
        }
    }

    public function getRoles($user_id)
    {
        $role_model = new RbacUserRoleModel();

        return $role_model->getUserRole($user_id);
    }

    public function getNods($role_ids)
    {
        $node_model = new RbacNodeModel();

        return $node_model->whereIn('id', $role_ids)->select('id')->get();
    }

    public function create($data)
    {
        $admin_row = $this->model()->create($data);
        if(!$admin_row)return 0;

        return $admin_row->id;
    }

    public function update(AdminModel $row, $data)
    {
        return $row->update($data);
    }

    public function model()
    {
        static $model;

        if(!$model){
            $model = new AdminModel();
        }

        return $model;
    }

    public function getRow($id)
    {

        $row = $this->model()->find($id);

        if(!$row){
            throw new AuthException("操作员不存在:" . $id);
        }

        return $row;
    }

    public function getLimit($limit, Collection $collection)
    {
        $list = $this->model()->getLimit($limit, $collection)->toArray();

        $data = $list['data'];

        $model = new RbacUserRoleModel();

        foreach ($data as &$row){
            $row['role_name'] = $model->where('user_id' , $row['id'])->value('role_name');
        }

        $list['data'] = $data;

        return $list;
    }

    public function delete($id)
    {
        return $this->model()->where('id', $id)->delete();
    }
}