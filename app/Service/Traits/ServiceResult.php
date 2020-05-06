<?php namespace App\Service\Traits;


use App\Http\Codes\Code;

class ServiceResult{

    private $error;
    private $code;
    private $useService;

    public function setError($error, $code = null){
        $this->error = $error;
        $this->code  = $code;
    }

    public function getCode(){
        return $this->code ? : ($this->error ? Code::fail : null);
    }

    public function getMsg(){
        return ($this->error ? : $this->getUseMsg());
    }

    public function isSuccess(){
        return $this->code == Code::success || (is_null($this->code) && is_null($this->error) && $this->useIsSuccess());
    }

    public function use($service){
        $this->useService = $service;
    }

    private function hasUsed(){
        return $this->useService;
    }

    private function getUseMsg(){
        return $this->hasUsed() ? $this->useService->getMsg() : '';
    }

    private function useIsSuccess(){
        return $this->hasUsed() ? $this->useService->isSuccess() : true;
    }

}