<?php namespace App\Http\Controllers\Admin;


use App\Http\Codes\Code;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Models\MemberAccountLogModel;
use App\Service\Count\Count;
use App\Service\Export\OfficialExcel;
use App\Service\Export\Export;
use Illuminate\Support\Collection;


class MemberAccountLogController extends Controller{

	protected $model;

	function module(){
	}

	function rule(){
	}

	public function __construct(MemberAccountLogModel $model){
		parent::__construct();

		$this->model = $model;
	}

	public function exportSelect(ApiVerifyRequest $request)
    {
        $ids      = $request->get('ids');

        $ids      = is_array($ids) ? $ids : explode(',', $ids);

        $fileName = $request->get('file_name');

        if(!$ids){
            return Response::error(Code::not_exists, "请选择要导出的记录");
        }

        $export_service = new Export($fileName, new OfficialExcel);

        return $export_service->exportById($this->model, $ids, $request);
    }

    public function exportFilter(ApiVerifyRequest $request)
    {
        $fileName = $request->get('file_name');

        $export_service = new Export($fileName, new OfficialExcel);

        return $export_service->exportByFilter($this->model, $request);
    }

    public function pointLog(ApiVerifyRequest $request)
    {
        $accountModel  = new MemberAccountLogModel();
        $list          = $accountModel->getPointLimit(new Collection($request->all()), $this->limitNum())->toArray();

        $count_service = new Count(new Collection($request));

        $point_history_total  = $count_service->getPointHistoryTotal();
        $point_used_total     = $count_service->getUsedPointTotal();

        $list['point_history_total'] = $point_history_total;
        $list['point_used_total']    = $point_used_total;
        $list['point_left_total']    = $point_history_total - $point_used_total;

        return Response::success('', $list);
    }

}