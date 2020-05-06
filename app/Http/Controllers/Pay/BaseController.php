<?php namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use App\Service\Users\MemberUser;
use Libs\Response;
use App\Service\Token\AccessToken;
use App\Service\Users\CachierUser;

abstract class BaseController extends Controller
{

    public function module()
    {
        return 'pay';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function user()
    {
        return CachierUser::getInstance();
    }

    public function member()
    {
        return MemberUser::getInstance();
    }

    public function getExeId()
    {
        return $this->user()->getAttribute('exe_id');
    }

    public function getCashierId()
    {
        return $this->user()->getId();
    }

}