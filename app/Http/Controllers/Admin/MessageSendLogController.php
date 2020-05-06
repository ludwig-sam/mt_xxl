<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use Libs\Arr;
use Libs\Response;
use Libs\Time;
use App\Repositorys\Admin\MessageSendLogRepository;

class MessageSendLogController extends BaseController {

    private $repository;

    public function rule()
    {

    }

    public function __construct(MessageSendLogRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function lists(ApiVerifyRequest $request){
    	$where = Arr::getIfExists($request->all(),['type','status']);
    	$lists = $this->repository->limit($this->limitNum(),$where);
		return Response::success('查找成功',$lists);
    }
}