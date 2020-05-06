<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use App\Http\Codes\LeiCode;
use Libs\Arr;
use Libs\Response;
use App\Models\MchModel;
use App\Repositorys\Admin\MchRepository;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;
use App\Service\Mch\Mch;

class MchController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Admin\Mch();
    }

    public function __construct(MchRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function add(ApiVerifyRequest $request)
    {
        $refund_pwd = $request->get('refund_pwd');

        if(!$refund_pwd){
            $request->offsetSet('refund_pwd', 888888);
        }

        if(!$id = $this->repository->save($request->all())){
            return Response::error(LeiCode::Mchs_add_fail, '网络错误');
        }
        return Response::success('添加成功', compact('id'));
    }
    public function update(ApiVerifyRequest $request)
    {
        if(!$row = $this->repository->find(intval($request['id'])))
        {
            return Response::error(LeiCode::not_exists,'商户不存在');
        }

        Mch::ifStopThrow($row->id);

        if(!$id = $this->repository->update($request->all())){
            return Response::error(LeiCode::Mchs_update_fail, '网络错误');
        }

	    self::note("更新商户:", "商户ID:".$request['id']);

        return Response::success('',$id);
    }
    public function lists(ApiVerifyRequest $request)
    {
            return Response::success('',$this->repository->page($this->limitNum(),$request));
    }
    public function level(ApiVerifyRequest $request)
    {
        $data=$this->repository->getLevel($request->get('mch_id'));
        return Response::success('',compact('data'));
    }
    public function show($id)
    {
    	if(!$this->repository->check($id)){
		    return Response::error(LeiCode::not_exists, '该商户不存在');
	    }
        if(!$data=$this->repository->show($id)){
            return Response::error(LeiCode::not_exists, '该商户不存在');
        }
        return Response::success('',$data);
    }
    public function delete($id)
    {
        if(!$this->repository->delete($id)){
            return Response::error(LeiCode::Mchs_delete_fail, '该商户不存在，无法删除');
        }
	    $detial = "删除了商户ID:".$id;
	    self::note("删除商户:",$detial);
        return Response::success('已删除',$id);
    }

    public function cardList(ApiVerifyRequest $request, \App\Repositorys\Admin\CardRepository $repository){
        if( !$data = $repository->mchCardLimit($request,$this->user()->getMchId(),$this->limitNum())){
            return Response::success('网络错误', []);
        }
        return Response::success('', $data->toArray());
    }

    function getAll()
    {
        $mch_model = new MchModel();

        $list = $mch_model->select('id', 'name')->get();

        return Response::success('', $list);
    }

    public function setHot(ApiVerifyRequest $request){
		$data = Arr::getIfExists($request->all(),['id','is_hot']);

		if(!$mch = MchModel::find($data['id'])){
			return Response::error(WeiCode::not_exists,'该商户不存在');
		}

		if(intval($data['is_hot']) > 1){
			$data['is_hot'] = 1;
		}else if(intval($data['is_hot']) < 0){
			$data['is_hot'] = 0;
		}

		$mch->is_hot = $data['is_hot'];
		$mch->save();

		return Response::success('修改成功');
    }

	public function exportSelect(ApiVerifyRequest $request)
	{
		$ids      = $request->get('ids');

		$ids      = is_array($ids) ? $ids : explode(',', $ids);

		$fileName = $request->get('file_name');

		if(!$ids){
			return Response::error(Code::not_exists, "请选择要导出的商户");
		}

		$export_service = new Export($fileName, new OfficialExcel);

		return $export_service->exportById($this->repository, $ids, $request);
	}

	public function exportFilter(ApiVerifyRequest $request)
	{
		$fileName = $request->get('file_name');

		$export_service = new Export($fileName, new OfficialExcel);

		return $export_service->exportByFilter($this->repository, $request);
	}

	public function stop($id)
    {
        $mch_row = $this->repository->find($id);

        if(!$mch_row){
            return Response::error(Code::not_exists, '商户不存在');
        }

        $is_stop = abs($mch_row->is_stop - 1);

        $this->repository->stop($mch_row->id, $is_stop);

        return Response::success("修改成功", [
            'is_stop' => $is_stop
        ]);
    }
}