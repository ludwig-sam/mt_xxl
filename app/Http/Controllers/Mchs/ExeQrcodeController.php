<?php namespace App\Http\Controllers\Mchs;


use App\Service\Mch\Exe;

class ExeQrcodeController extends BaseController {

    public function rule()
    {
    }

    public function __construct()
    {
        $this->notNeedPermission();

        $this->notNeedToken();

        parent::__construct();
    }

    private function service() : Exe
    {
        return $this->single(function (){
            return new Exe();
        });
    }

    public function payQrcode($id)
    {
        return $this->service()->payQrcode($id);
    }
}