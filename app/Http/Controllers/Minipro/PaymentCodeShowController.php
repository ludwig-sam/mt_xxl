<?php namespace App\Http\Controllers\Minipro;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Minipro\PaymentCodeRule;
use Endroid\QrCode\Factory\QrCodeFactory;


class PaymentCodeShowController extends BaseController
{

    public function __construct()
    {
        $this->notNeedToken();
        parent::__construct();
    }

    public function rule()
    {
        return new PaymentCodeRule();
    }

    public function qrcode(ApiVerifyRequest $request)
    {
        $code    = $request->get('code');
        $factory = new QrCodeFactory();
        $qrcode  = $factory->create($code);

        return response($qrcode->writeString())->header('Content-Type', $qrcode->getContentType());
    }

    public function barcode(ApiVerifyRequest $request)
    {
        $code      = $request->get('code');
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $string    = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        return response($string)->header('Content-Type', 'image/png');
    }

}