<?php namespace App\Http\Controllers\Mchs;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Response;
use App\Http\Codes\WeiCode;
use App\Repositorys\Admin\ExeRepository;

class ExeController extends BaseController
{

    private $repository;

    public function rule()
    {
        return new Rules\Mchs\Exe();
    }

    public function __construct(ExeRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    public function create(ApiVerifyRequest $request)
    {
        if (!$row = $this->repository->createForMch($request, $this->user()->getMchId())) {
            return Response::error(WeiCode::create_exe_fail, '网络错误');
        }
        $id = $row->id;
        return Response::success('添加成功', compact('id'));
    }

    public function show($id)
    {
        return Response::success('获取成功', $this->repository->getAndCheck($id, $this->user()->getMchId()));
    }

    public function lists(ApiVerifyRequest $request)
    {
        return Response::success('', $this->repository->limit($this->limitNum(), $request, $this->user()->getMchId()));
    }

    public function update(ApiVerifyRequest $request)
    {
        if (!$this->repository->updateForMch($request, $this->user()->getMchId())) {
            return Response::error(WeiCode::update_exe_fail, '网络错误');
        }

        self::note("更新收银机", "更新了收银机ID:" . $request->get('id') . "的内容");
        return Response::success('更新成功');
    }

    public function delete($id)
    {

        $row = $this->repository->getAndCheck($id, $this->user()->getMchId());

        if (!$this->repository->removeForMch($row)) {
            return Response::error(WeiCode::delete_exe_fail, '删除失败');
        }

        self::note("删除收银机", "删除了收银机ID:" . $id);
        return Response::success('删除成功');
    }
}