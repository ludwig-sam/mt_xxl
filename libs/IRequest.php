<?php namespace Libs;


use Libs\Str;

class IRequest{

    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    static private $request;
    static private $request_id;

    static function getRequestId()
    {
        $request    = request()->request;

        if(self::$request != $request || !self::$request_id){

            self::$request = $request;

            self::$request_id = self::buildRequestId();
        }

        return self::$request_id;
    }

    private static function buildRequestId()
    {
        return Str::rand(32);
    }

}