<?php namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class RewardsConfigModel extends Model {

    protected $table = 'rewards_config';

    protected $fillable = [
        'event', 'card_id', 'reward_card_id'
    ];

    public $timestamps = false;

    public function get($event, $card_id)
    {
        return $this->where('event', $event)->where('card_id', $card_id)->first();
    }



}

