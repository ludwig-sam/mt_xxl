<?php namespace App\Service\Traits;


use Overtrue\Socialite\HasAttributes;

/**
 * Traits ResultTrait
 * @property ServiceResult $result
 * @package App\Service\Traits
 */
trait ResultTrait{
    use HasAttributes;

    private $result;

    public function result(){
        $this->result = $this->result ? : new ServiceResult();
        return $this->result;
    }

    public function use(ServiceResult $service){
        $this->result()->use($service);
    }

    public function setError($error, $code = null){
        $this->result()->setError($error, $code);
    }

    public function __get($property)
    {
        if($property == 'result'){
            return $this->result();
        }
        return $this->getAttribute($property);
    }
}