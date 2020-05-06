<?php

namespace Providers\Request;

use Abstracts\Offsetable;
use Abstracts\RequestFilterInterface;
use Libs\Unit;

class MoneyFenFilter implements RequestFilterInterface {


    public function filter(Array $config, Offsetable &$request)
    {
        foreach ($config as $fieldName){
            if($request->offsetExists($fieldName)){
                $request->offsetSet($fieldName, Unit::yuntoFen($request->offsetGet($fieldName)));
            }
        }

        $request->save();
    }


}