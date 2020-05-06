<?php

namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    public function module()
    {
        return 'pub';
    }

}
