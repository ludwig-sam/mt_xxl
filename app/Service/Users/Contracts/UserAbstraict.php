<?php namespace App\Service\Users\Contracts;

use Overtrue\Socialite\HasAttributes;

abstract class UserAbstraict{
    use HasAttributes;

    protected $model;

    protected function __construct()
    {
    }

    public function model(){
        return $this->model;
    }

    public function init($user)
    {
        $this->model = $user;

        $data = $user->toArray();
        foreach ($data as $name => $value){
            $this->setAttribute($name, $value);
        }
    }

    abstract public function getId();
    abstract public function setId($id);
    abstract public function getMchId();
    abstract public function setMchId($mchId);
    abstract public function isMember();

}
