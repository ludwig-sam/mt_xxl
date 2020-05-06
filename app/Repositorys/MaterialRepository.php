<?php namespace App\Repositorys;

use App\Exceptions\MaterialException;
use App\Models\MaterialModel;
use App\DataTypes\MaterialTypes;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class MaterialRepository extends Repository {
    use Newable;

    public function model()
    {
        return MaterialModel::class;
    }

    public function getCompleteInfo($materialId, $materialType)
    {
            if(!in_array($materialType, MaterialTypes::getTypes())){
                throw new MaterialException("获取素材时候错误的类型：" . $materialType);
            }

        return $this->model->getCompleteInfo($materialId, $materialType);
    }

}