<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use App\Http\Codes\LeiCode;
use Libs\Filter;
use App\Repositorys\Admin\MchCategoryRepository;
use Libs\Response;


class MchCategoryController extends BaseController
{

    private $repository;

    public function rule()
    {
        return new Rules\Admin\MchCategory();
    }

    public function __construct(MchCategoryRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function show($id)
    {
        $data = $this->repository->find($id);
        if (!$data) {
            return Response::error(LeiCode::not_exists, '该分类不存在');
        }
        return Response::success('', $data);
    }

    public function update(ApiVerifyRequest $request)
    {
        if (!$this->repository->find(intval($request['id']))) {
            return Response::error(LeiCode::not_exists, '该分类不存在');
        }
        if (!$this->repository->update($request->all())) {
            return Response::error(LeiCode::mchCategory_update_fail, '网络错误');
        }
        $detial = "更新了商户分类ID:" . $request['id'];
        self::note("更新商户分类:", $detial);
        return Response::success('修改成功');
    }

    public function add(ApiVerifyRequest $request)
    {
        if (!$id = $this->repository->save($request->all())) {
            return Response::error(LeiCode::mchCategory_add_fail, '网络错误');
        }
        return Response::success('', compact('id'));
    }

    public function delete($id)
    {
        if (!$this->repository->find($id)) {
            return Response::error(LeiCode::not_exists, '该分类不存在');
        }
        if (!$this->repository->delete($id)) {
            return Response::error(LeiCode::mchCategory_delete_fail, '该分类不存在，无法删除');
        }
        $detial = "删除了商户分类ID:" . $id;
        self::note("删除商户分类:", $detial);
        return Response::success('删除成功');
    }

    public function list()
    {
        return Response::success('', $this->repository->all());
    }

    public function chStatus(ApiVerifyRequest $request)
    {
        $isUse = Filter::int($request->get('is_use'));
        $id    = Filter::int($request->get('id'));

        $match = $this->repository->find($id);

        if (!$match) {
            return Response::error(Code::not_exists, '分类不存在');
        }

        if (!$match->fill(['is_use' => $isUse])->save()) {
            return Response::success(Code::upload_fail, '修改失败');
        }
        $detial = "更新了商户分类ID:" . $request['id'] . "的状态";
        self::note("更新商户分类状态:", $detial);
        return Response::success('修改成功');
    }

}