<?php namespace App\Repositorys\Admin;


use App\Models\ArticleModel;
use Bosnadev\Repositories\Eloquent\Repository;


class ArticleRepository extends Repository {

    public function model()
    {
        return ArticleModel::class;
    }

    public function limit($limit)
    {
        return $this->model->paginate($limit);
    }



}