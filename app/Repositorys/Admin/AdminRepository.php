<?php namespace App\Repositorys\Admin;


use App\Models\AdminModel;
use App\Models\RbacRoleModel;
use Libs\Arr;
use Libs\Response;
use App\Http\Codes\LeiCode;

class AdminRepository {

    private $model;

    public function __construct(AdminModel $model)
    {
        $this->model = $model;
    }

    public function findMe($id)
    {
        return $this->model->select('user_name as name','person_name','headurl','status','last_login_ip','last_login_at','created_at','updated_at','mch_id', 'temporary_mch_id')->where('id',$id)->first();
    }

    public function list()
    {
        return RbacRoleModel::select('id','name')->get();
    }

    public function save($data)
    {
        $this->model->fill($data);
        return $this->model->save();
    }

    public function update($id,$data)
    {
        $strArr=['user_name','person_name','headurl','password'];
        $arr =Arr::getIfExists($data,$strArr);

        if($arr==null)
        {
            return [LeiCode::not_null,'修改不能为空'];
        }

        $me = $this->model->find($id);

        if($me==null)
        {
            return [LeiCode::not_exists,'用户不存在无法修改'];
        }

        if(isset($arr['password']))
        {
           if(password_verify($data['old_password'],$me->password))
           {
               $me->fill($arr)->save();
               return true;
           }
           else
           {
               return [LeiCode::old_password_error,'旧密码错误'];
           }
        }

        $me->fill($arr)->save();

        return true;

    }


}