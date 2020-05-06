<?php namespace App\Http\Controllers\Mchs;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Mchs\Withdraw;
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

    public function create(ApiVerifyRequest $request)
    {
        $request = $this->service()->mergeBanckInfo(new Collection($request));
        $request = $this->service()->createFill($request, $this->user());
        $ret     = $this->service()->create($request);

        return self::response($ret, '添加');
    }

    public function limit(ApiVerifyRequest $request)
    {
        $req    = new Collection($request);
        $mch_id = $this->user()->getMchId();
        $req    = $req->merge(compact('mch_id'));
        $list   = $this->service()->model()->mchLimit($this->limitNum(), $req);

        return self::success('', $list);
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $row     = $this->service()->getAndCheck($id);
        $request = $this->service()->mergeBanckInfo(new Collection($request->only('apply_money', 'bank_card_id')));
        $ret     = $this->service()->update($row, $request);

        return self::response($ret, '更新');
    }

    public function get($id)
    {
        $row                = $this->service()->getAndCheck($id);
        $detail             = toArray($row);
        $mch_row            = new MchRow($row->mch_id);
        $detail['mch_name'] = $mch_row->name();

        return self::success('', $row);
    }

    public function delete($id)
    {
        $row = $this->service()->getAndCheck($id);

        $ret = $this->service()->delete($row);

        return self::response($ret, '删除');
    }

    public function count()
    {
        $bill_service    = new Bill();
        $mch_id          = $this->user()->getMchId();
        $dispose_total   = $this->service()->model()->disposTotalWithMch($mch_id);
        $pending_total   = $this->service()->model()->pendingTotalWithMch($mch_id);
        $apply_total     = $this->service()->model()->total();
        $bill_in_total   = $bill_service->inTotalBelongTheMch($mch_id);
        $bill_left_total = max($bill_in_total - $dispose_total, 0);

        return self::success('', compact('dispose_total', 'pending_total', 'bill_left_total', 'apply_total'));
    }

    public function export(ApiVerifyRequest $request)
    {
        $request        = new Collection($request);
        $repository     = $this->service()->model();
        $fileName       = $request->get('file_name');
        $mch_id         = $this->user()->getMchId();
        $request        = $request->merge(compact('mch_id'));
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