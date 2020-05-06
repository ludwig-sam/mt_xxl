<?php

namespace Providers\Request;

use Abstracts\Offsetable;
use Abstracts\RequestFilterInterface;
use Libs\Unit;

class MoneyYunFilter implements RequestFilterInterface {


    public function filter(Array $config, Offsetable &$request)
    {
        foreach ($config as $fieldName){
            if($request->offsetExists($fieldName)){
                $request->offsetSet($fieldName, Unit::fentoYun($request->offsetGet($fieldName)));
            }
        }
    }

}

