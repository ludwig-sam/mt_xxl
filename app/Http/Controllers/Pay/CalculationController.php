<?php namespace App\Http\Controllers\Pay;


use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Service\Card\CardService;
use App\Service\Pay\Payment;
use Providers\RequestOffsetableAdapter;

class CalculationController extends BaseController {


    public function rule()
    {
    }

    public function index(ApiVerifyRequest $request){
        $paymentService    = new Payment();
        $cardService       = new CardService();

        $cardCode   = $request->get('card_code', '');
        $couponCode = $request->get('coupon_code', '');

        list($cardId, $couponId) = $cardService->getCardIdByCode([$cardCode, $couponCode]);

        $request->offsetSet('card_id', $cardId);
        $request->offsetSet('coupon_id', $couponId);
        $request->offsetSet('mch_id', $this->user()->getMchId());

        return Response::success('', [
            'total_amount'   => $request->get('total_amount'),
            'amount'         => $paymentService->calculationAmount(new RequestOffsetableAdapter($request)),
            'coupon_code'    => $couponCode,
            'coupon_id'      => (string)$couponId,
            'card_code'      => $cardCode,
            'card_id'        => (string)$cardId
        ]);
    }

}