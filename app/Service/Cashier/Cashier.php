<?php namespace App\Service\Cashier;

use App\DataTypes\ExeOpratorStatus;
use App\Service\Service;
use App\Exceptions\PayMchException;
use App\Models\ExeOpratorModel;


class Cashier extends Service {

    public function login($userName, $password){
        $exeOpratorModel = new ExeOpratorModel();
        $oprator         = $exeOpratorModel->getByUserName($userName);

        $this->check($oprator, $password);

        $lastLoginAt = $oprator->last_login_at;

        $oprator->loginSuccess();

        $oprator->last_login_at = $lastLoginAt;

        return $oprator;
    }

    private function check($oprator, $password){
        if(!$oprator){
            throw new PayMchException('收银员用户名或密码错误', PayMchException::user_name_err);
        }

        if(!password_verify($password,$oprator->password)){
            throw new PayMchException('收银员用户名或密码错误', PayMchException::password_err);
        }

        if($oprator->status == ExeOpratorStatus::disabled){
            throw new PayMchException("收银员已被禁用", PayMchException::cashier_disable);
        }
    }

}