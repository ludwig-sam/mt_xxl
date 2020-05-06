<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午12:13
 */

namespace App\Service\Material\Contracts;

use Abstracts\UploaderInterface;
use App\Exceptions\MaterialException;
use App\Http\Codes\Code;
use App\Models\ReplyMaterialModel;
use Illuminate\Support\Collection;
use App\Models\MaterialModel;


trait MaterialComFunTrait
{
    private static $cdn;

    public function limit(Collection &$material){
        $materialModel = new MaterialModel();
        $list          = $materialModel->limit($this->getType(), $material)->toArray();

        return $this->limitFilter($list) ? : $list;
    }

    protected function limitFilter($list)
    {
        return $list;
    }

    private function getRow($materialId)
    {
        $materialModel = new MaterialModel();
        return $materialModel->getCompleteInfo((int)$materialId, $this->getType());
    }

    private function check($row)
    {
        if(!$row){
            throw new MaterialException('素材不存在', Code::not_exists);
        }
    }

    public function get($materialId)
    {
        $row  = $this->getRow($materialId);

        $this->check($row);

        return $this->getFilter($row);
    }

    protected function getFilter($row)
    {
        return $row;
    }

    protected function cdn() : UploaderInterface
    {
        if(!self::$cdn){
            self::$cdn = app(UploaderInterface::class);
        }
        return self::$cdn;
    }

    private function checkReplyIsUserMaterial($material_id)
    {
        $reply_model = new ReplyMaterialModel();

        $m = $reply_model->where('material_id', $material_id)->first();

        if($m){
            throw new MaterialException("素材已被自动回复占用，不能删除");
        }
    }

    public  function delete($material_id)
    {
        $row = $this->get($material_id);

        $this->check($row);

        $this->checkReplyIsUserMaterial($material_id);

        return $row->delete();
    }
}