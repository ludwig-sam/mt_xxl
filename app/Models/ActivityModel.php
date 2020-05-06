<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 2018/8/22
 * Time: 16:03
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityModel extends Model
{

    protected $table = "activaty";

    protected $fillable = [
        "name", " start_at", "end_at", "pic", "detail", "sort"
    ];

    public $timestamps = false;

    public function activatyLimit($limit)
    {
        return $this->filterQuery()->paginate($limit);
    }

    public function filterQuery()
    {
        return $this->orderBy('sort', "asc")->orderBy('id', 'desc');
    }

    public function setDetailAttribute($value)
    {
        $this->attributes['detail'] = json_encode($value);
    }

    public function getDetailAttribute($value)
    {
        return $this->attributes['detail'] = json_decode($value);
    }
}