<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/27
 * Time: 下午2:15
 */

namespace App\Models\Traits;


use Libs\Str;

trait CallTableAble
{
    public static function table($as = null)
    {
        $table = (new self)->getTable();

        $table = Str::first($table, ' as ');

        if (!is_null($as)) {
            $table .= ' as ' . trim($as);
        }

        return $table;
    }

    public static function f($name, $as = null)
    {
        return self::table() . '.' . $name . (is_null($as) ? '' : " as {$as}");
    }
}