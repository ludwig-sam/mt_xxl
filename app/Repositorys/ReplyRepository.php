<?php namespace App\Repositorys;


use App\Models\ReplyModel;
use Bosnadev\Repositories\Eloquent\Repository;

class ReplyRepository extends Repository {
    use Newable;


    public function model()
    {
        return ReplyModel::class;
    }

    public function getUsingByENameEKey($eventName, $eventKey)
    {
        return $this->model->getReplyAndMaterial(['event_name' => $eventName, 'event_key'  => $eventKey, 'is_stop' => 0]);
    }

}