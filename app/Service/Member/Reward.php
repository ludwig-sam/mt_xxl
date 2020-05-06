<?php namespace App\Service\Member;

use App\Models\RewardsModel;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;


abstract class Reward extends Service {


    protected $user;

    public function __construct(UserAbstraict $user)
    {
        $this->user = $user;
    }

    public function model()
    {
        static $model;

        if(!$model){
            $model = new RewardsModel();
        }

        return $model;
    }

    abstract function getRow();
    abstract function add($card_id);

}