<?php namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class RewardsModel extends Model {

    protected $table = 'rewards';

    protected $fillable = [
        'event','wx_card_id','member_id','status','card_id'
    ];

    public $timestamps = false;


    public function getReword($event, $member_id)
    {
        return $this->where('event', $event)->where('member_id', $member_id)->first();
    }

    public function addReward($event, $member_id, $card_id)
    {
        return $this->create([
            'event'      => $event,
            'member_id' => $member_id,
            'card_id'   => $card_id
        ]);
    }



}

