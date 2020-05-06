<?php namespace Libs;



class Tree{

    public static function path(&$arr, $pid = 0, Array $path = [], $nodeName = ''){

        foreach ($arr as &$row){
            $curPath   = $path;
            $curPath[] = $row[$nodeName];

            if($row['pid'] == $pid){
                $row['path'] = $curPath;
                self::path($arr, $row['id'], $curPath, $nodeName);
            }
        }
    }

    public static function layer($arr, $pid = 0){
        $result = [];
        foreach ($arr as $row){
            if($row['pid'] == $pid){
                $row['son'] = self::layer($arr, $row['id']);
                $result[] = $row;
            }
        }

        return $result;
    }

}


