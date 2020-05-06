<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Repositorys\Admin\OprationLogRepository;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;

class OprationLogController extends BaseController {

    private $repository;

    public function rule()
    {

    }

    public function __construct(OprationLogRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function lists(ApiVerifyRequest $request){
		return Response::success('查找成功',$this->repository->limit($this->limitNum(),$request));
    }

	public function exportSelect(ApiVerifyRequest $request)
	{
		$ids      = $request->get('ids');

		$ids      = is_array($ids) ? $ids : explode(',', $ids);

		$fileName = $request->get('file_name');

		$request['mch_id'] = $this->user()->getMchId();

		if(!$ids){
			return Response::error(Code::not_exists, "请选择要导出的记录");
		}

		$export_service = new Export($fileName, new OfficialExcel());

		return $export_service->exportById($this->repository, $ids, $request);
	}

	public function exportFilter(ApiVerifyRequest $request)
	{
		$fileName = $request->get('file_name');

		$export_service = new Export($fileName, new OfficialExcel);

		$request['mch_id'] = $this->user()->getMchId();

		return $export_service->exportByFilter($this->repository, $request);
	}
}