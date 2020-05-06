<?php namespace App\Repositorys;


use App\Models\ReplyKeywords;
use Bosnadev\Repositories\Eloquent\Repository;

class ReplyKeywordsRepository extends Repository {
    use Newable;

    public function model()
    {
        return ReplyKeywords::class;
    }

    public function getAllKeywords()
    {
        $keywords = $this->makeModel()->get();
        return array_pluck($keywords,'keyword');
    }

}