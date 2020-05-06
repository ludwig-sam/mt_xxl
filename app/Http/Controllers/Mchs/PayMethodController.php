<?php namespace App\Http\Controllers\Mchs;

use Libs\Response;
use App\Repositorys\Mchs\PayMethodRepository;

class PayMethodController extends BaseController{

	private $repository;

	public function rule(){
	}

	public function __construct(PayMethodRepository $repository){
		parent::__construct();
		$this->repository = $repository;
	}

	public function lists(){
		return Response::success('', $this->repository->limit($this->limitNum()));
	}

}