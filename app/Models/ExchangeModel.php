<?php namespace App\Models;


use App\DataTypes\CardStatus;
use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Database\Eloquent\Model;

class ExchangeModel  extends Model {

    use DynamicWhereTrait;

    protected $table = 'card_exchange';

    protected $fillable = [
        'card_id','exchange_name','exchange_value'
    ];

    public $timestamps = false;

    public function getList($limit, $collection){

        $model = new CardModel();

        $model = $model->where($this->dynamicEqWhere([
            'ce.exchange_name'
        ], $collection))
        ->when($collection->get('type'), function ($query) use($collection) {
            return $query->whereIn('type', (array)$collection->get('type'));
        });

        return $model
            ->join($this->table . ' as ce', 'card.id', '=', 'ce.card_id')
            ->leftJoin((new MchModel())->getTable() . ' as mch', 'mch.id', '=', 'card.mch_id')
            ->where('status', CardStatus::sending)
            ->select('card.*', 'card.card_id as wx_card_id', 'ce.id', 'ce.card_id', 'ce.exchange_name', 'ce.exchange_value', 'mch.name as mch_name')
            ->orderBy('ce.id', 'desc')
            ->paginate($limit);

    }

}

