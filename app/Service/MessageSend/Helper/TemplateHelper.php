<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/11
 * Time: 下午1:43
 */

namespace App\Service\MessageSend\Helper;


use Libs\Time;

class TemplateHelper
{

    static function getName($line)
    {
        $pattern = '/{{(.*?)\.DATA}}/';

        if(!preg_match($pattern, $line)){
            return ['', $line];
        }

        $split = explode('：', $line);

        if(count($split) == 1){
            array_unshift($split, '');
        }

        $name = $split[1];
        $title = $split[0];

        $name = preg_replace($pattern, '\1', $name);

        return [$name, $title];
    }

    static function parse($str)
    {
        $data = [];

        $lines = explode("\n", $str);

        foreach($lines as $line){
            list($name, $title) = self::getName($line);

            if(!$title && !$name) continue;

            $data[] = ['title' => $title, 'name' => $name, 'value' => ''];
        }

        return $data;
    }

    static function toData($data)
    {
        $result = [];

        foreach($data as $row){
            $name = $row['name'];

            if(!$name) continue;

            $item = ['value' => $row['value']];

            if(isset($row['color'])){
                $item['color'] = $row['color'];
            }

            $result[$name] = $item;
        }

        return $result;
    }

    static function systemVar(&$params)
    {
        $params['date_at'] = Time::date();
    }

    static function replacePlaceholder($data, $url, $params)
    {
        self::systemVar($params);

        foreach($params as $paramKey => $paramValue){

            if(is_array($paramValue)) continue;

            foreach($data as &$item){
                $item['value'] = str_replace(self::getCurePlaceholder($paramKey), $paramValue, $item['value']);
            }
            $url = str_replace(self::getCurePlaceholder($paramKey), $paramValue, $url);
        }

        return [$data, $url];
    }

    static function getCurePlaceholder($key)
    {
        return "[:{$key}]";
    }

}