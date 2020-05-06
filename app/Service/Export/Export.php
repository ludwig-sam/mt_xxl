<?php namespace App\Service\Export;

use Abstracts\UploaderInterface;
use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Http\Codes\Code;
use Libs\Response;
use Libs\Str;
use App\Models\FilesModel;
use App\Service\Export\Contracts\Exportable;
use App\Service\Export\Contracts\ExportSupportInterface;
use App\Service\Service;


class Export extends Service {


    private $save = true;

    private $file_name;
    private $exportable;

    function __construct($file_name, Exportable $exportable)
    {
        $this->file_name  = $file_name;
        $this->exportable = $exportable;
    }

    function exportById(ExportSupportInterface $export_support, $ids, $request)
    {
        $list           = $export_support->exportByIds($ids, $request);

        list($header, $result) = $export_support->cells($list);

        return $this->export($header, $result);
    }

    function exportByFilter(ExportSupportInterface $export_support, $request)
    {
        $list = $export_support->filterNoLimit($request);

        list($header, $result) = $export_support->cells($list);

        return $this->export($header, $result);
    }

    function uploader() : UploaderInterface
    {
        static $uploder;

        if($uploder)return $uploder;

        $uploder = app(UploaderInterface::class);

        return $uploder;
    }

    function fullFileName()
    {

        $dir = storage_path('temp/');

        if(!$this->file_name){
            $this->file_name = time() . Str::rand(30);
        }

        return $dir . $this->file_name . ".xlsx";
    }

    function remove()
    {
        file_exists($this->fullFileName()) && unlink($this->fullFileName());
    }

    function upload()
    {
        if(!$this->uploader()->uploadFile($this->fullFileName())){

            $this->remove();

            throw new ExceptionCustomCodeAble(json_encode($this->uploader()->result()->getMsg()), Code::upload_fail);
        }

        $this->remove();

        $this->saveToDb();

        return $this->uploader();
    }

    function saveToDb()
    {
        $file_model = new FilesModel();

        $file_model->create([
            'path'          => $this->uploader()->result()->getData()->get('path'),
            'hkey'          => $this->uploader()->result()->getData()->get('hash'),
            'original_file' => $this->fullFileName()
        ]);
    }

    function check()
    {
    }

    function export($header, $result)
    {

        $this->save && $this->check();

        $this->exportable->setFileName($this->fullFileName());

        $this->exportable->export($header, $result, $this->save);

        if($this->save) {
            $this->upload();
        }

        return Response::success('导出完成', [
            'path' => $this->uploader()->result()->getData()->get('path')
        ]);
    }
}