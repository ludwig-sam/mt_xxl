<?php namespace App\Http\Controllers\Minipro;


use Libs\Filter;
use Libs\Response;
use App\Models\CardModel;
use App\Models\ExchangeModel;
use App\Service\Activity\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExchangeShopController extends BaseController {


	public function rule()
    {
	}

    public function list(Request $request)
    {
        $card_model     = new CardModel();
        $model          = new ExchangeModel();
        $req_collection = new Collection($request);

        $req_collection->offsetSet('exchange_name', 'POINT');

        $list  = $model->getList($this->limitNum(), $req_collection);

        return Response::success('', ['list' => $list]);

    }

    public function exchange(Request $request){
	    $id = Filter::int($request->get("id"));
	    $exchangeService = new Exchange();
	    $user            = $this->user();

	    return Response::success('', [
            'card_data'  => $exchangeService->exchange($id, $user)
        ]);
    }

}