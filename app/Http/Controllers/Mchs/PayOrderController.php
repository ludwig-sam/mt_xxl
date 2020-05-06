<?php namespace App\Http\Controllers\Mchs;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Repositorys\Admin\PayOrderRepository;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;
use Illuminate\Support\Collection;

class PayOrderController extends BaseController
{

    private $repository;

    public function rule()
    {
    }

    public function __construct(PayOrderRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function lists(ApiVerifyRequest $request)
    {
        $request['mch_id'] = $this->user()->getMchId();
        $request = new Collection($request);
        $data = $this->repository->limit($request, $this->limitNum())->toArray();

        $data['payOrderCount'] = $this->repository->payOrderCount($request);

        return Response::success('', $data);
    }

    public function exportSelect(ApiVerifyRequest $request)
    {
        $ids = $request->get('ids');

        $ids = is_array($ids) ? $ids : explode(',', $ids);

        $fileName = $request->get('file_name');

        $request['mch_id'] = $this->user()->getMchId();

        if (!$ids) {
            return Response::error(Code::not_exists, "请选择要导出的记录");
        }

        $export_service = new Export($fileName, new OfficialExcel);

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