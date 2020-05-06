<?php namespace App\Models;

use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FansModel extends Model {

    use DynamicWhereTrait;

    protected $table = 'fans';

    protected $fillable = [
        "id","openid","nickname","is_subscribe","headurl","subscribe_at","sex","city","mobile",
        "updated_at"
    ];

    protected $dates = [
        "created_at",
        "updated_at"
    ];

    public function getLimit($limit, Collection $collection)
    {
        $search_bys = [
            "nickname"
        ];

        $definds = [
            [
                "nickname",
                "like",
                "nickname"
            ],
            [
                "sex",
                'sex'
            ]
        ];

        $search_by = $collection->get('search_by');

        if(in_array($search_by, $search_bys)){
            $collection->offsetSet($search_by, $collection->get('keywords'));
        }

        $model = $this;

        $this->dynamicAnyWhere($model, $definds, $collection);

        return $model->paginate($limit);
    }
}

