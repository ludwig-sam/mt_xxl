<?php namespace App\Http\Controllers\Receive;


use App\Http\Controllers\Controller;

class BaseController extends Controller {


    public function rule()
    {

    }

    public function module()
    {
        return 'receive';
    }

}