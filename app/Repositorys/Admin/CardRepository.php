<?php namespace App\Repositorys\Admin;


use Libs\Arr;
use App\Models\CardModel;
use App\DataTypes\CardStatus;
use App\DataTypes\CardTypes;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class CardRepository extends Repository {

	public function model() {
		return CardModel::class;
	}

	public function mchCardLimit($request, $mch_id, $limit) {
		$where =[
			[Arr::getIfExists($request->all(), ['type'])]
		];

		$card_ids = DB::table('mch_cards as m_c')
			->select("card_id")
			->where('mch_id',$mch_id)
			->get();

		$card_arr = [];
		foreach($card_ids as $item){
			array_push($card_arr,$item->card_id);
		}

		return $this->model
			->where(function($query) use ($mch_id,$card_arr){
				$query->whereIn("id",$card_arr)
					->orWhere(function($query)  use ($card_arr){
						$query->where('mch_id','=',0);
					})
					->orWhere(function($query)  use ($mch_id){
						$query->where('mch_id','=',$mch_id);
					});
				})
			->where($where)
			->whereNotIn('type',[CardTypes::member_card])
			->paginate($limit);

	}

	public function miniproCardLimit($mch_id,$limit) {
		$card_ids = DB::table('mch_cards as m_c')
			->select("card_id")
			->where('mch_id',$mch_id)
			->get();

		$card_arr = [];
		foreach($card_ids as $item){
			array_push($card_arr,$item->card_id);
		}

		return $this->model
			->where(function($query) use ($mch_id,$card_arr){
				$query->whereIn("id",$card_arr)
					->orWhere(function($query)  use ($card_arr){
						$query->where('mch_id','=',0);
					})
					->orWhere(function($query)  use ($mch_id){
						$query->where('mch_id','=',$mch_id);
					});
			})
			->where('end_time', '>=', time())
			->where('status', CardStatus::sending)
			->where('quantity', '>', 0)
			->whereNotIn('type',[CardTypes::member_card])
			->paginate($limit);
	}

	public function exeNormalCouponList($mchId)
    {
        return $this->model->whereIn('mch_id', [$mchId, 0])
            ->where('status', CardStatus::sending)
            ->where('quantity', '>', 0)
            ->whereNotIn('type', [CardTypes::member_card])
            ->select('id','title','mch_id')
            ->get();
    }
}