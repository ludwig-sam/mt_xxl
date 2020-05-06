<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Admin\FictitiousCardCodeRule;
use App\Models\FictitiousCardCodeModel;
use App\Service\Account\FictitiousCardCodeService;
use App\Service\Export\Export;
use App\Service\Export\OfficialExcel;
use Illuminate\Support\Collection;

class FictitiousCardCodeController extends BaseController
{


    public function rule()
    {
        return new FictitiousCardCodeRule();
    }

    function service():FictitiousCardCodeService
    {
        return $this->newSingle(FictitiousCardCodeService::class);
    }

    public function limit(ApiVerifyRequest $request)
    {
        $request = new Collection($request);
        $list    = $this->service()->model()->manageLimit($this->limitNum(), $request);

        return self::success('', $list);
    }

    public function export(ApiVerifyRequest $request)
    {
        $repository     = new FictitiousCardCodeModel();
        $fileName       = $request->get('file_name');
        $export_service = new Export($fileName, new OfficialExcel());

        return $export_service->exportByFilter($repository, $request);
    }

    public function exportSelect(ApiVerifyRequest $request)
    {
        $repository     = new FictitiousCardCodeModel();
        $ids            = (array)$request->get('ids');
        $fileName       = $request->get('file_name');
        $export_service = new Export($fileName, new OfficialExcel());

        return $export_service->exportById($repository, $ids, $request);
    }
}