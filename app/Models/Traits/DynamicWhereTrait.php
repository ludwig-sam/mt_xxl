<?php namespace App\Models\Traits;


use Libs\Str;
use Illuminate\Support\Collection;

Trait DynamicWhereTrait
{

    private function dateRange(&$model, Collection &$collection, $sName = 'sdate', $eName = 'edate', $fieldName = 'created_at')
    {
        $sdate = $collection->get($sName);
        $edate = $collection->get($eName);

        switch (true) {
            case $sdate && $edate:
                $model->whereBetween($fieldName, [$sdate, $edate]);
                break;
            case $sdate:
                $model->where($fieldName, '>=', $sdate);
                break;
            case $edate:
                $model->where($fieldName, '<=', $edate);
                break;

        }
    }

    private function dynamicEqWhere($definds, Collection &$offsetable)
    {
        $where = [];
        foreach ($definds as $defind) {

            $requestKey = Str::last($defind, '.');

            if ($offsetable->offsetExists($requestKey)) {
                $where[$defind] = $offsetable->get($requestKey);
            }
        }

        return $where;
    }

    private function dynamicAnyWhere(&$model, $definds, Collection &$collection)
    {
        $where = self::combinWhereArr($definds, $collection);

        foreach ($where as $row) {
            if ($row[1] == 'in') {
                $model = $model->whereIn($row[0], $row[2]);
            } else {
                $model = $model->where($row[0], $row[1], $row[2]);
            }
        }

        return $model;
    }

    public static function combinWhereArr($definds, Collection &$collection)
    {
        $result = [];
        foreach ($definds as $defindRow) {
            $key = $defindRow[0];

            if (count($defindRow) < 3) {
                $con     = '=';
                $val_key = $defindRow[1];
            } else {
                $con     = $defindRow[1];
                $val_key = $defindRow[2];
            }

            if ($con == 'in') {
                $result[] = [$key, $con, $val_key];
                continue;
            }

            if ($collection->get($val_key) === null) {
                continue;
            }

            if ($con == 'like') {
                $val = self::likeWhere($val_key, $collection);
            } else {
                $val = self::eqWhere($val_key, $collection);
            }

            $result[] = [$key, $con, $val];
        }

        return $result;
    }

    public static function eqWhere($val_key, Collection $collection)
    {
        return $collection->get($val_key);
    }

    public static function likeWhere($val_key, Collection $collection)
    {
        if (strchr($val_key, '%') !== false) {
            $old_key = $val_key;
            $val_key = trim($val_key, '%');
            $val     = str_replace($val_key, $collection->get($val_key), $old_key);
        } else {
            $val = '%' . $collection->get($val_key) . '%';
        }

        return $val;
    }

}