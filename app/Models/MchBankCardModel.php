<?php namespace App\Models;


use App\Models\Traits\CallTableAble;
use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MchBankCardModel extends Model
{

    use DynamicWhereTrait;
    use CallTableAble;

    protected $table = 'mch_bank_card';

    protected $fillable = [
        'card_number', 'bank_name', 'initial_bank', 'name', 'mobile', 'mch_id', 'created_at', 'updated_at'
    ];

    public $timestamps = false;

    function limit($limit, Collection $request)
    {
        $model = $this->orderBy(self::f('id'), 'desc');

        $model = $this->dynamicAnyWhere($model, [
            [
                'mch_id',
                'mch_id'
            ]
        ], $request);

        return $model->paginate($limit);
    }

}

