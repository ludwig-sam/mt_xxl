<?php

namespace App\Http\Controllers;



trait IterceptTrait
{
    private $needToken = true;
    private $needPermission = true;

    final protected function notNeedToken()
    {
        $this->needToken = false;
    }

    final protected function notNeedPermission()
    {
        $this->needPermission = false;
    }

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next){
            $this->__before();
            return $next($request);
        });
    }

    function __before()
    {

    }

}
