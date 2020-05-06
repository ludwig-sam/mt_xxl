<?php namespace App\Repositorys;


use App\Models\CardModel;
use App\Service\Users\AdminUser;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class CardRepository extends Repository {

	public function model() {
		return CardModel::class;
	}

	public function limit( $limit ) {
        return DB::table('card')
            ->leftJoin('mch','card.mch_id','=','mch.id')
            ->select('card.id','card.card_id as wx_card_id','card.title','card.quantity'
                ,'card.date_info','card.logo_url','card.type','card.background_pic_url'
                ,'card.created_at'
                ,'card.updated_at','card.begin_time','card.end_time','mch.name as mch_name','card.color')
            ->whereNotIn('type',['MEMBER_CARD'])
            ->where('can_exchange','<>',1)
            ->whereNull('card.deleted_at')
            ->orderBy('id','desc')
            ->paginate($limit);
	}
}