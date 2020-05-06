<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IterceptTrait;
use App\Service\Auth\Intercept;
use App\Service\Users\AdminUser;

abstract class BaseController extends Controller
{

    use IterceptTrait;

    public function module()
    {
        return 'admin';
    }

    final protected function user()
    {
        return AdminUser::getInstance();
    }

    public function __before()
    {
        //非常重要，自动加载auth
        config(['jwt.user' => \App\Models\AdminModel::class]);
        config(['auth.defaults.guard' => 'admin']);

        $intercept = new Intercept();

        if ($this->needToken) {
            AdminUser::getInstance()->init($intercept->verify());
        }

        if ($this->needPermission) {
            $intercept->verifyPermission($this->module(), $this->user());
        }
    }

}