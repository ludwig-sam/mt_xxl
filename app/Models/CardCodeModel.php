<?php namespace App\Models;

use App\DataTypes\CardCodeStatus;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use Libs\Time;
use App\Models\Traits\DynamicWhereTrait;
use App\Service\Users\MemberUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CardCodeModel extends Model {

    use DynamicWhereTrait;

    protected $table = 'card_code';


    protected $guarded = [
        "id"
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];


    public function myInvalidCardsLimit($limit, Collection $collection)
    {
        $model = (new CardModel())
            ->where(function ($query){
                return $query->where('card_code.status', CardCodeStatus::consume)
                    ->orWhere('card_code.end_time', '<=', \time());
            });


        return $this->limit($model, $limit);
    }

    public function myCardsLimit($limit, Collection $collection)
    {
        $model = (new CardModel())
            ->where('card_code.status', CardCodeStatus::receive)
            ->where('card_code.end_time', '>', \time());

        return $this->limit($model, $limit);
    }

    public function limit($model, $limit)
    {
        return $model
            ->join('card_code','card_code.card_id','=','card.id')
            ->leftJoin('mch','card.mch_id', '=','mch.id')
            ->select('card_code.id','card_code.id as code_id','card_code.card_id','card.card_id as wx_card_id','card_code.code_no as wx_code_no','card_code.status','card.logo_url','card.background_pic_url','card.color','card.title','card.type','card_code.start_time','card_code.end_time','mch.name as mch_name')
            ->where('card.type',"<>",'MEMBER_CARD')
            ->where('member_id', MemberUser::getInstance()->getId())
            ->orderBy('card_code.id','desc')
            ->paginate($limit);
    }

    public function getMemberId($code, $card_id)
    {
        return $this->where('card_id', $card_id)->where('code_no', $code)->value('member_id');
    }

    public function validCardList($member_id, $limit, $mch_id, $type)
    {
        return (new CardModel())
            ->join('card_code','card_code.card_id','=','card.id')
            ->where('card_code.status', CardCodeStatus::receive)
            ->where('card_code.end_time', '>', \time())
            ->where('card.type',"<>", CardTypes::member_card)
            ->when($type, function($query) use($type){
                return $query->whereIn('card.type', (array)$type);
            })
            ->whereIn('card.mch_id', [0 , $mch_id])
            ->where('card.status', CardStatus::sending)
            ->where('member_id', $member_id)
            ->orderBy('card_code.id','desc')
            ->select('card_code.id as code_id', 'card.can_overlay','card.type', 'card.discount', 'card.least_cost','card_code.card_id','card_code.code_no as wx_code_no','card.logo_url','card.background_pic_url','card.color','card.title','card_code.start_time','card_code.end_time', 'card.reduce_cost')
            ->paginate($limit);
    }

    public function getCardIdByCodeId($code_id)
    {
        return $this->where('id', $code_id)->value('card_id');
    }

    public function getStatus($id)
    {
        return $this->where('id', $id)->value('status');
    }

}

