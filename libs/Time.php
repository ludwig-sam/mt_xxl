<?php namespace Libs;


Class Time
{

    private static $path  = null;
    private static $start = [];

    static function date($time = null, $format = 'Y-m-d H:i:s')
    {
        return $time !== null ? date($format, $time) : date($format);
    }

    static function dateAfter($d)
    {
        return strtotime('+' . $d . ' day');
    }

    static function formatReset($format, $date)
    {
        return self::date(strtotime($date), $format);
    }

    static function startToday()
    {
        return date('Y-m-d') . ' 00:00:00';
    }

    static function endToday()
    {
        return date('Y-m-d') . ' 23:59:59';
    }

    static function dateBefore($d)
    {
        return strtotime('-' . $d . ' day');
    }

    static public function start($path = null)
    {
        if (is_null($path)) {
            $path = time() . mt_rand(0, 10000000);
        }
        self::$path         = $path;
        self::$start[$path] = microtime(true);
    }

    static public function end($path = null)
    {
        if (is_null($path)) {
            $path = self::$path;
        }

        $old = isset(self::$start[$path]) ? self::$start[$path] : 0;

        $t = microtime(true) - $old;
        $t = $t * 1000000;
        return round($t) / 1000000;
    }
}