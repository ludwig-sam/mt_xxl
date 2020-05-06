<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use App\Http\Codes\Code;
use Libs\Response;
use App\Repositorys\Admin\ArticleRepository;

class ArticleController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Admin\Article();
    }

    public function __construct(ArticleRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function show($id)
    {
        $data = $this->repository->find($id);
        if(!$data){
            return Response::error(Code::not_exists, '文章不存在');
        }
        return Response::success('', $data);
    }

    public function lists()
    {
        return Response::success('', $this->repository->limit($this->limitNum()));
    }

    public function create(ApiVerifyRequest $request)
    {
        if(!$id = $this->repository->create($request->all())){
            return Response::error(Code::create_fial, '添加失败');
        }
        return Response::success('添加成功', compact('id'));
    }

}