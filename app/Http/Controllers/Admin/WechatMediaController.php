<?php namespace App\Http\Controllers\Admin;



use App\Http\Codes\Code;
use Libs\Response;
use App\Service\Wechat\Media;
use Illuminate\Http\Request;
use Providers\UploadFactory;

class WechatMediaController extends BaseController {


    public function rule()
    {

    }

    public function uploadImage(Request $request)
    {
        $service  = new Media();

        $uploader = UploadFactory::image($request->get('file'), 'file');

        if(!$uploader->up()){
            return Response::error(Code::upload_fail, $uploader->getError());
        }

        $uploadedInfo    = $uploader->getUploadedInfo();
        $tmpFile         = $uploadedInfo['path'];
        $uploadWechatRet = $service->uploadArticleImage($tmpFile);

        unlink($tmpFile);

        if(!$uploadWechatRet){
            return Response::error(Code::upload_fail, $service->result()->getMsg());
        }

        return Response::success('上传成功', ['path' => $service->result()->getData()->get('url')]);
    }
}