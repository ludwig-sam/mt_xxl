<?php namespace App\Http\Controllers\Admin;


use App\Http\Requests\ApiVerifyRequest;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;
use App\Service\Pay\Bill;
use App\Service\Recharge\RechargeService;
use Illuminate\Support\Collection;
use Libs\FloatNum;

class RechargeController extends BaseController
{

    public function rule()
    {
    }

    private function service():RechargeService
    {
        return $this->newSingle(RechargeService::class);
    }

    public function limit(ApiVerifyRequest $request)
    {
        $list = $this->service()->rechargeLimit($this->limitNum(), $request);

        return self::success('', $list);
    }

    public function count(ApiVerifyRequest $request)
    {
        $bill_service  = new Bill();
        $request       = new Collection($request);
        $total         = $this->service()->total($request);
        $consume_total = $bill_service->totalBalancePay($request);
        $left_total    = FloatNum::reduce($total, $consume_total);
        $left_total    = max($left_total, 0);

        return self::success('', compact('total', 'left_total', 'consume_total'));
    }

    public function export(ApiVerifyRequest $request)
    {
        $request        = new Collection($request);
        $repository     = $this->service()->model();
        $fileName       = $request->get('file_name');
        $export_service = new Export($fileName, new OfficialExcel());

        return $export_service->exportByFilter($repository, $request);
    }

    public function exportSelect(ApiVerifyRequest $request)
    {
        $request        = new Collection($request);
        $repository     = $this->service()->model();
        $ids            = (array)$request->get('ids');
        $fileName       = $request->get('file_name');
        $export_service = new Export($fileName, new OfficialExcel());

        return $export_service->exportById($repository, $ids, $request);
    }
}