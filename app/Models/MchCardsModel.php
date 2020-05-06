<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MchCardsModel extends Model {


    protected $table = 'mch_cards';

    protected $fillable = [
        'mch_id', 'card_id'
    ];

    public $timestamps = false;

    public function getMchs($card_id)
    {
        $mchs =  $this->where('card_id', (int)$card_id)->get()->toArray();

        return array_column($mchs, 'mch_id');
    }

}

