<?php namespace App\Http\Controllers\Mchs;

use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Repositorys\Admin\CardRepository;

class CardController extends BaseController{

	private $repository;

	public function rule(){

	}

	public function __construct(CardRepository $repository){
		parent::__construct();
		$this->repository = $repository;
	}

	public function cardList(ApiVerifyRequest $request){
		return Response::success('', $this->repository->mchCardLimit($request,$this->user()->getMchId(),$this->limitNum()));
	}

	public function exeNormalCouponList()
    {
        $mchId   = $this->user()->getMchId();
        return Response::success('', $this->repository->exeNormalCouponList($mchId));
    }
}