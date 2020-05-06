<?php

namespace App\Http\Controllers\Pub;


use Abstracts\UploaderInterface;
use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\UploadRule;
use Libs\Response;
use App\Models\FilesModel;

class UploadController extends BaseController
{
    private $model;

    public function __construct(FilesModel $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    public function rule()
    {
        return new UploadRule();
    }

    public function upload(UploaderInterface $uploader, ApiVerifyRequest $request)
    {
        $content = file_get_contents($request->get('file'));

        if(!$uploader->uploadString($content)){
            return Response::error(Code::upload_fail, $uploader->result()->getMsg(), $uploader->result()->getCode());
        }

        $data = $uploader->result()->getData();

        $key = $data->get('hash');
        if(!$model = $this->model->where('hkey', $key)->first()){
            $this->model->path = $data->get('path');
            $this->model->hkey = $data->get('hash');
            $this->model->save();
            $model = $this->model;
        }

        $data['id'] = $model->id;

        return Response::success('', $data);
    }

}
