<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Response;
use App\Repositorys\Admin\AdvertPositionRepository;

class AdvertPositionController extends BaseController {

    private $repository;
    public function rule()
    {
    }

    public function __construct(AdvertPositionRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    public function lists()
    {
        return Response::success('',$this->repository->all());
    }

}