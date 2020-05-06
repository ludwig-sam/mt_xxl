<?php namespace App\Service\Mch;


use Libs\Route;
use App\Models\ExeModel;
use Providers\Curd\CurdServiceTrait;
use App\Service\Service;
use Endroid\QrCode\Factory\QrCodeFactory;

class Exe extends Service
{

    use CurdServiceTrait;

    function model():ExeModel
    {
        return $this->single(function(){
            return new ExeModel();
        });
    }

    public function payQrcode($id)
    {
        $this->getAndCheck($id);

        $factory = new QrCodeFactory();

        $qrcode = $factory->create($this->complateUrl($id));

        return response($qrcode->writeString())->header('Content-Type', $qrcode->getContentType());
    }

    public function complateUrl($id)
    {
        return Route::named('h5pay_scan_code') . '?exe_id=' . $id;
    }
}