<?php namespace App\Http\Controllers\Admin;



use App\Http\Codes\Code;
use App\Jobs\ProcessMaterialUpdate;
use Libs\Response;
use App\Models\MaterialModel;
use App\DataTypes\MaterialTypes;
use App\Models\ReplyMaterialModel;
use App\Service\Listener\MaterialUpdateListener;
use App\Service\Material\Factory;
use App\Service\Wechat\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Providers\UploadFactory;

class MaterialController extends BaseController {


    public function rule()
    {
    }

    public function create($type, Request $request)
    {
        $materialInstance = Factory::make($type);
        $material         = new Collection($request->all());

        if(!$materialInstance->upload($material)){
            return Response::error(Code::fail, '上传提示:' . $materialInstance->result()->getMsg());
        }

        if(!$materialInstance->save(new MaterialModel(), $material)){
            return Response::error(Code::fail, '保存失败');
        }

        return Response::success('添加成功');
    }

    public function update($materialId, Request $request)
    {
        $materialModel = new MaterialModel();

        $row = $materialModel->find($materialId);

        if(!$row){
            return Response::error(Code::not_exists, '素材不存在');
        }

        $materialInstance = Factory::make($row->type);
        $material         = new Collection($request->all());

        $material->offsetSet('type', $row->type);
        $material->offsetSet('id', $row->id);

        if(!$materialInstance->updateUpload($material)){
            return Response::error(Code::fail, '上传提示:' . $materialInstance->result()->getMsg());
        }

        if(!$materialInstance->save($row, $material)){
            return Response::error(Code::fail, '保存失败');
        }

	    self::note("更新素材", $materialId);

        return Response::success('修改成功');
    }

    public function limit($type, Request $request)
    {
        $request['limit'] = $this->limitNum();
        $materialInstance = Factory::make($type);
        $reqCellection    = new Collection($request->all());
        $list             = $materialInstance->limit($reqCellection);
        return Response::success('', $list);
    }

    public function get($materialId)
    {
        $materialModel = new MaterialModel();
        $type          = $materialModel->getTypeById($materialId);

        $materialInstance = Factory::make($type);
        $row              = $materialInstance->get($materialId);
        return Response::success('', $row);
    }

    public function delete($materialId)
    {
        $materialModel = new MaterialModel();
        $type          = $materialModel->getTypeById($materialId);

        if(!$type){
            return Response::error(Code::not_exists, "不存在");
        }

        $materialInstance = Factory::make($type);
        if(!$materialInstance->delete($materialId)){
           return Response::error(Code::fail, "删除失败");
        }

        return Response::success('删除成功');
    }

    public function uploadThumb(Request $request)
    {
        $thumb      = $request->get('thumb_file');
        $uploader   = UploadFactory::image($thumb, null);
        $wechatMediaService = new Media();

        if(!$uploader->up()){
            return Response::error(Code::upload_fail, $uploader->getError());
        }

        $thumbPath = $uploader->getUploadedInfo()['path'];

        if(!$wechatMediaService->uploadThumb($thumbPath)){
            unlink($thumbPath);

            return Response::error($wechatMediaService->result()->getCode(), $wechatMediaService->result()->getMsg());
        }

        unlink($thumbPath);

        return Response::success('上传成功', [
            'thumb_media_id'  => $wechatMediaService->result()->getData()->get('media_id'),
            'thumb_media_url' => $wechatMediaService->result()->getData()->get('url')
        ]);
    }

    public function pullWechat($type)
    {
        MaterialTypes::checkOfficialType($type);

        $this->dispatch(new ProcessMaterialUpdate(new MaterialUpdateListener($type), 0, $type));

        return Response::success('任务创建成功');
    }
}