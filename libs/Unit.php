<?php namespace Libs;


class Unit
{


    public static function formatSize($size, $reverse_times = 0)
    {
        $unit_arr = ['B', 'K', 'M', 'G', 'T'];
        return $size >= 1024 ? self::formatSize($size / 1024, $reverse_times + 1) : sprintf('%.2f', $size) . (isset($unit_arr[$reverse_times]) ? $unit_arr[$reverse_times] : ' unit oversize');
    }

    public static function toSize($format_size)
    {
        $unit      = substr($format_size, -1);
        $unit_size = rtrim($format_size, $unit);
        switch ((string)strtoupper($unit)) {
            case 'B':
                $final_size = $unit_size;
                break;
            case 'K':
                $final_size = $unit_size * 1024;
                break;
            case 'M':
                $final_size = $unit_size * pow(1024, 2);
                break;
            case 'G':
                $final_size = $unit_size * pow(1024, 3);
                break;
            default:
                $final_size = 0;
                break;

        }
        return $final_size;
    }

    public static function fentoYun($fen, $pointLen = 2)
    {
        return sprintf("%.{$pointLen}f", $fen / 100);
    }

    public static function floatPoint($money, $pointLen = 2)
    {
        return sprintf("%.{$pointLen}f", $money);
    }

    public static function yuntoFen($yuan)
    {
        if (is_array($yuan)) return $yuan;
        return floor($yuan * 100);
    }

}
