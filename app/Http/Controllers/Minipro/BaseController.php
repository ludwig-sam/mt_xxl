<?php namespace App\Http\Controllers\Minipro;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IterceptTrait;
use App\Service\Auth\Intercept;
use App\Service\Users\MemberUser;

abstract class BaseController extends Controller{

    use IterceptTrait;

    public function module()
    {
        return 'minipro';
    }

    final protected function user()
    {
        return MemberUser::getInstance();
    }

    public function __before()
    {
        config(['jwt.user' => \App\Models\MemberModel::class]);
        config(['auth.defaults.guard' => 'member']);

        if($this->needToken){
            $intercept = new Intercept();

            MemberUser::getInstance()->init($intercept->verify());
        }
    }
}