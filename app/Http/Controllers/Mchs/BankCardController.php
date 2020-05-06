<?php namespace App\Http\Controllers\Mchs;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Mchs\Withdraw;
use App\Service\MchWithdraw\MchBankCardService;
use App\Service\Row\MchBanckCardRow;
use Illuminate\Support\Collection;

class BankCardController extends BaseController
{

    function service():MchBankCardService
    {
        return $this->newSingle(MchBankCardService::class);
    }

    public function rule()
    {
        return new Withdraw();
    }

    public function create(ApiVerifyRequest $request)
    {
        $request = new Collection($request);
        $request = $this->service()->createFill($this->user(), $request);
        $ret     = $this->service()->create($request);

        return self::response($ret, '添加');
    }

    public function limit(ApiVerifyRequest $request)
    {
        $req    = new Collection($request);
        $mch_id = $this->user()->getMchId();
        $req    = $req->merge(compact('mch_id'));
        $list   = $this->service()->model()->limit(self::limitNum(), $req);

        return self::success('', $list);
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $row = $this->service()->getAndCheck($id);
        $ret = $this->service()->update($row, $request);

        return self::response($ret, '更新');
    }

    public function get($id)
    {
        $row = $this->service()->getAndCheck($id);

        return self::success('', $row);
    }

    public function delete($id)
    {
        $banck_card_row = new MchBanckCardRow($id);

        $this->service()->checkCanDelete($banck_card_row);

        $ret = $this->service()->delete($banck_card_row);

        return self::response($ret, '删除');
    }

}