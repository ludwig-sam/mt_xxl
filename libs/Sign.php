<?php namespace Libs;


class Sign
{

    public static function serialize($data)
    {
        foreach ($data as &$item) {
            if (is_array($item)) {
                $item = json_encode($data);
            }
        }

        return $data;
    }

    public static function filter(Array $data, $trim = [], $true_match = true)
    {
        return Arr::filter($data, $trim, $true_match);
    }

    public static function ksort(Array $data)
    {
        ksort($data);

        return $data;
    }

    public static function sort(Array $data)
    {
        sort($data);

        return $data;
    }

    public static function reset(Array $data)
    {
        reset($data);

        return $data;
    }

    public static function linkString(Array $para)
    {
        $arg = [];
        foreach ($para as $key => $val) {
            $arg[] = $key . "=" . $val;
        }

        $str = join('&', $arg);

        return $str;
    }

    public static function urlencode(string $str)
    {
        return urlencode($str);
    }

    public static function strip(string $str)
    {
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }

        return $str;
    }

    public static function encrypt($str, $token)
    {
        return md5($str . $token);
    }
}

