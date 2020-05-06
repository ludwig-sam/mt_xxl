<?php namespace Libs;


Class Route
{
    const get    = 'get';
    const post   = 'post';
    const input  = 'input';
    const delete = 'delete';
    const put    = 'put';

    static private $routeSet;

    static public function named($name, $parameters = [])
    {
        return route($name, (array)$parameters);
    }

    static public function action()
    {
        $str            = request()->route()->action['controller'];
        $fullController = Str::first($str, '@');
        $controller     = Str::last($fullController, '\\');
        $controller     = Str::first($controller, 'Controller');
        $method         = Str::last($str, '@');

        return [$controller, $method, $fullController];
    }

    static public function group($prefix, $callBack, $middleware)
    {
        $route = \Route::prefix($prefix);
        $middleware && $route->middleware(is_array($middleware) ? $middleware : [$middleware]);
        $route->group($callBack);
    }

    static public function any($route, $controllerName = null)
    {
        return self::match($route, [self::get, self::post, self::input, self::delete], $controllerName);
    }

    static public function match($route, $methods, $controllerName = null):\Libs\RoutePacking
    {
        $methods = (array)$methods;

        self::parseSetRoute($route);

        if ($controllerName instanceof \Closure) {
            $finalPath = $controllerName;
        } else {
            $controllerName     = self::sureControllerName($controllerName);
            $controllerFormated = self::replaceDelimter($controllerName);
            $finalPath          = str_replace("@", "Controller@", $controllerFormated);
        }

        return new \Libs\RoutePacking(\Route::match($methods, $route, $finalPath));
    }

    static private function parseSetRoute($route)
    {
        self::$routeSet = explode('.', $route);
    }

    static private function sureControllerName($controllerName)
    {
        return is_null($controllerName) ? Str::first(self::$routeSet[0], '/') : $controllerName;
    }

    static private function replaceDelimter($path)
    {
        $limiters = explode('.', $path);
        return implode('\\', Arr::format($limiters, 'ucfirst'));
    }
}

