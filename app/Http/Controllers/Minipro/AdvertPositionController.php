<?php namespace App\Http\Controllers\Minipro;

use App\Http\Requests\ApiVerifyRequest;
use Libs\Arr;
use Libs\Response;
use App\Repositorys\Admin\AdvertPositionRepository;

class AdvertPositionController extends BaseController {

	private $repository;

	public function rule() {

	}

	public function __construct( AdvertPositionRepository $repository ) {
		parent::__construct();
		$this->repository = $repository;
	}

    public function lists(ApiVerifyRequest $request)
    {
    	$position_key = Arr::getIfExists($request->all(),['position_key']);
        return Response::success('', $this->repository->limit($position_key));
    }
}