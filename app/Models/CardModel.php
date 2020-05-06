<?php namespace App\Models;

use App\DataTypes\CardTypes;
use Libs\Arr;
use Libs\Unit;
use App\Models\Traits\FromTrait;
use App\Service\Wechat\Card;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class CardModel extends Model
{

    use SoftDeletes;
    use FromTrait;

    protected $table = 'card';


    protected $fillable = [
        "card_id", "title", "total_quantity", "quantity", "consume_quantity", "receive_quantity", "status", "not_overdue", "date_info",
        "begin_time", "end_time", "logo_url", "mch_id", "type", "discount", "least_cost", "reduce_cost", "can_overlay", "updated_at", "deleted_at",
        "can_exchange",'get_limit','background_pic_url','color'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function setNotOverdueAttribute($value)
    {
        $this->attributes['not_overdue'] = intval(strtoupper($value) == 'DATE_TYPE_PERMANENT');
    }

    public function setMchIdAttribute($value)
    {
        $mch_id = (int)$value;

        if ($this->isMemberCard()) {
            $mch_id = 0;
        }

        $this->attributes['mch_id'] = $mch_id;

    }

    public function setGetLimitAttribute($value)
    {
        $this->attributes['get_limit'] =  intval($value);
    }

    public function setCanOverlayAttribute($value)
    {

        $value = Arr::enum($value, [0, 1]);

        if ($this->isMemberCard()) {
            $value = 1;
        }

        $this->attributes['can_overlay'] = $value;
    }

    public function setDateInfoAttribute($value)
    {
        $this->attributes['date_info'] = json_encode($value);
    }

    public function setLeastCostAttribute($value)
    {
        $this->attributes['least_cost'] = Unit::fentoYun($value);
    }

    public function setReduceCostAttribute($value)
    {
        $this->attributes['reduce_cost'] = Unit::fentoYun($value);
    }

    private function isMemberCard()
    {
        return $this->getAttribute('type') == CardTypes::member_card;
    }

    public function ticketLimit($limit, Collection $collection)
    {
        $model = $this->when($collection->get('type'), function ($query) use($collection){
            return $query->where('type' , $collection->get('type'));
        })->when(!$collection->get('type'), function ($query) use($collection){
            return $query->whereNotIn("type", [CardTypes::member_card]);
        });

        return $this ->limit($model, $limit, $collection);
    }

    public function memberCardLimit($limit, Collection $collection)
    {
        $model = $this->where("type", CardTypes::member_card);

        return $this ->limit($model, $limit, $collection);
    }

    public function limit($model, $limit, Collection $collection)
    {
        $model
            ->when($collection->get('title'), function ($query) use($collection){
                return $query->where('title' , 'like', "%". $collection->get('title') . "%");
            })
            ->when($collection->get('card_id'), function ($query) use($collection){
                return $query->where('card_id' , '=', $collection->get('card_id'));
            })
            ->orderBy('id', 'desc')
            ->select('id', 'type', 'card_id', 'title', 'logo_url', 'total_quantity' ,'status', 'quantity', 'receive_quantity', 'consume_quantity', 'grant_quantity', 'created_at', 'updated_at' , 'not_overdue', 'begin_time', 'end_time');

        return $model->paginate($limit);
    }

    public function exchangLimit($limit, Collection $collection)
    {
        return $this
            ->from('c')
            ->join((new ExchangeModel())->getTable() . ' as e', 'e.card_id', '=', 'c.id')
            ->when($collection->get('type'), function ($query) use($collection){
                return $query->where('type' , $collection->get('type'));
            })
            ->when($collection->get('title'), function ($query) use($collection){
                return $query->where('title' , 'like', "%". $collection->get('title') . "%");
            })
            ->when($collection->get('card_id'), function ($query) use($collection){
                return $query->where('card_id' , '=', $collection->get('card_id'));
            })
            ->select('c.id', 'type', 'c.card_id', 'title', 'logo_url', 'total_quantity' ,'status', 'quantity', 'receive_quantity', 'consume_quantity', 'grant_quantity', 'c.created_at', 'c.updated_at', 'e.exchange_value' , 'c.not_overdue', 'c.begin_time', 'c.end_time')
            ->paginate($limit);
    }

    public function getWithMch($id)
    {
        return $this->from('c')
            ->leftJoin((new MchModel())->getTable()  . ' as m', 'm.id', '=', 'c.mch_id')
            ->select('c.*', 'm.name as mch_name')
            ->where('c.id', $id)
            ->first();
    }

    public function getCardIdByWxCardId($wx_card_id)
    {
        return $this->where('card_id', $wx_card_id)->value('id');
    }

    public function getByWxCardId($wx_card_id)
    {
        return $this->where('card_id', $wx_card_id)->first();
    }

    public function getTitle($id){
        return $this->where('id', $id)->value('title');
    }
}

