<?php namespace App\Models\Traits;


Trait ToArrayTrait{
    static function toList($map)
    {
        $result = [];
        foreach ($map  as $key => $row){
            $row['key'] = $key;
            $result[]   = $row;
        }
        return $result;
    }
}