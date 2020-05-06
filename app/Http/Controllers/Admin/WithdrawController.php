<?php namespace App\Http\Controllers\Admin;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Admin\Withdraw;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;
use App\Service\MchWithdraw\WithdrawService;
use App\Service\Pay\Bill;
use App\Service\Row\MchRow;
use Illuminate\Support\Collection;

class WithdrawController extends BaseController
{

    function service():WithdrawService
    {
        return $this->newSingle(WithdrawService::class);
    }

    public function rule()
    {
        return new Withdraw();
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $tmp  = $request->only('poundage', 'dispose_money', 'bank_order_no', 'status', 'remark');
        $row  = $this->service()->getAndCheck($id);
        $data = new Collection($tmp);
        $data = $this->service()->disposeFill($data, $this->user());

        $this->service()->disposeCheck($row, $data);

        $ret = $this->service()->update($row, $data);

        return self::response($ret, '更新');
    }

    public function limit(ApiVerifyRequest $request)
    {
        $row = $this->service()->model()->manageLimit($this->limitNum(), new Collection($request));

        return self::success('', $row);
    }

    public function get($id)
    {
        $row                = $this->service()->getAndCheck($id);
        $detail             = toArray($row);
        $mch_row            = new MchRow($row['mch_id']);
        $detail['mch_name'] = $mch_row->name();

        return self::success('', $detail);
    }

    public function count()
    {
        $bill_service    = new Bill();
        $dispose_total   = $this->service()->model()->disposTotal();
        $pending_total   = $this->service()->model()->pendingTotal();
        $apply_total     = $this->service()->model()->total();
        $bill_in_total   = $bill_service->inTotalBelongMch();
        $bill_left_total = max($bill_in_total - $dispose_total, 0);

        return self::success('', compact('dispose_total', 'pending_total', 'bill_left_total', 'apply_total'));
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