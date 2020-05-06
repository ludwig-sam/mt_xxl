<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/16
 * Time: 下午5:28
 */

namespace Libs;

Class RoutePacking
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function where($name, $reg):self
    {
        $this->route->where($name, $reg);
        return $this;
    }

    public function name($name):self
    {
        $this->route->name($name);
        return $this;
    }

    public function middleware($key)
    {
        $this->route->middleware($key);
        return $this;
    }

    public function match($mathods, $route, $hanlder)
    {
        $this->route->match($mathods, $route, $hanlder);
        return $this;
    }
}