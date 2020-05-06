<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use App\Http\Codes\LeiCode;
use App\Repositorys\Admin\TagsRepository;
use Libs\Response;

class TagsController extends BaseController
{

    private $repository;

    public function rule()
    {
        return new Rules\Admin\Tags();
    }

    public function __construct(TagsRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function show($id)
    {
        $data = $this->repository->find($id);
        if (!$data) {
            return Response::error(LeiCode::not_exists, '标签不存在');
        }
        return Response::success('', $data);
    }

    public function update(ApiVerifyRequest $request)
    {
        if (!$this->repository->find(intval($request['id']))) {
            return Response::error(LeiCode::not_exists, '该标签不存在');
        }
        if (!$this->repository->update($request->all())) {
            return Response::error(LeiCode::Tags_update_fail, '网络错误');
        }
        $detial = "更新了标签:id" . $request['id'];
        self::note("更新标签:", $detial);
        return Response::success('');
    }

    public function add(ApiVerifyRequest $request)
    {
        if (!$id = $this->repository->save($request->all())) {
            return Response::error(LeiCode::Tags_add_fail, '网络错误');
        }
        return Response::success('', compact('id'));
    }

    public function list()
    {
        $data = $this->repository->list();
        return Response::success("", compact('data'));
    }

}