<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use App\Service\Account\FictitiousCardCodeService;
use App\Service\Account\FictitiousCardService;
use App\Service\Row\FictitousCardRow;
use Illuminate\Support\Collection;
use Libs\Pay;

class FictitiousCardController extends BaseController
{


    public function rule()
    {
        return new Rules\Admin\FictitiousCard();
    }

    function service():FictitiousCardService
    {
        return $this->newSingle(FictitiousCardService::class);
    }

    public function create(ApiVerifyRequest $request)
    {
        $data                   = $request->all();
        $data['batch_order_no'] = Pay::orderNo();
        $data['quantity']       = $request->get('stock');
        $ret                    = $this->service()->create($data);
        $code_service           = new FictitiousCardCodeService();

        $ret && $code_service->genarateCode($ret->id);

        return self::response($ret, '添加');
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $card  = new FictitousCardRow($id);
        $stock = $request->get('stock');
        $data  = $request->except(['quantity', 'batch_order_no']);

        $this->service()->modifyStock($card, $stock);

        $ret = $this->service()->update($card, $data);

        return self::response($ret, '更新');
    }

    public function limit(ApiVerifyRequest $request)
    {
        $request = new Collection($request);
        $list    = $this->service()->model()->limit($this->limitNum(), $request);

        return self::success('', $list);
    }

    public function delete($id)
    {
        $row = new FictitousCardRow($id);
        $ret = $this->service()->delete($row);

        return self::response($ret, '删除');
    }

    public function get($id)
    {
        $row = $this->service()->getAndCheck($id);

        return self::success('', $row);
    }
}