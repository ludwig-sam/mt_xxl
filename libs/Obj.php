<?php namespace Libs;


Class Obj
{

    static public function attrs($obj, Array $keys = [])
    {
        $result = [];
        foreach ($keys as $name) {
            $result[$name] = $obj->$name;
        }
        return $result;
    }

    static public function name($obj)
    {
        return Str::last(get_class($obj), '\\');
    }

}



