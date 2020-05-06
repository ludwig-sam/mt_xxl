<?php namespace Tests\Pay;

use App\Exceptions\PayApiException;
use Libs\Pay;
use Libs\Response;
use App\Service\Pay\Discount;
use Illuminate\Support\Collection;
use Providers\RequestOffsetableAdapter;
use Tests\TestCase;

class DiscountTest extends TestCase{


    public function test_refundFail(){


        $discount_service = new Discount();

        $code = 824127600059;

        $request = request();

        $request->offsetSet('code', $code);
        $request->offsetSet('mch_id', 2);

        $off = new RequestOffsetableAdapter($request);

        $discount_service->pushCards(80, $code);

        $this->assertEquals(80, $discount_service->calculationAmount(100, $off));
    }

    public function test_cash(){


        $discount_service = new Discount();

        $code = '786666832607';

        $request = request();

        $request->offsetSet('code', $code);
        $request->offsetSet('mch_id', 2);

        $off = new RequestOffsetableAdapter($request);

        $discount_service->pushCards(105, $code);

        $this->assertEquals(0.1, $discount_service->calculationAmount(1, $off));
    }

}