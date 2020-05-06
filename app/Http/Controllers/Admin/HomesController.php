<?php namespace App\Http\Controllers\Admin;


use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\HomeRule;
use Libs\Response;
use Libs\Str;
use Libs\Unit;
use App\DataTypes\PayOrderStatus;
use App\Service\Count\Count;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class HomesController extends BaseController{


    private $request;

   public function rule()
   {
       return new HomeRule();
   }

   public function __construct(ApiVerifyRequest $request)
   {
       parent::notNeedPermission();

       $this->request = $request;
       parent::__construct();
   }

    public function index()
   {
        $count_service = new Count(new Collection($this->request));

        $mch_count              = $count_service->getMchCount();
        $member_count           = $count_service->getMemberCount();
        $member_make_up_count   = $count_service->getProfessionMakeUpCount();
        $card_count             = $count_service->getCardCount();
        $consum_card_count      = $count_service->getPayConsumeCardCount();
        $refund_amount_total    = $count_service->getRefundAmountTotal();
        $pay_amount_total       = Unit::floatPoint($count_service->getPayAmountTotal() - $refund_amount_total);

        $activity_member_count  = $count_service->activityMemberCount($this->request->get('activity_days', 30));
        $lost_member_count      = $count_service->lostMemberCount($this->request->get('lost_days', 90));


       return Response::success('', compact('member_count', 'activity_member_count', 'lost_member_count', 'mch_count', 'member_count', 'card_count', 'consum_card_count', 'pay_amount_total', 'refund_amount_total', 'member_make_up_count'));
   }

   public function collectionTotal(Request $request)
   {
       $count_service = new Count(new Collection($request));

       $result = [];
       $cat    = $request->get('cat');
       $mch_id = $request->get('mch_id');

       $ranges = $count_service->parseTotalRanges();


       switch ($cat){
           case 'refund':
               foreach ($ranges as $range => $range_date){
                   $result[] = [
                       'range' => $range,
                       'total' => $count_service->collectionTotal($mch_id, $range_date[0], $range_date[1], PayOrderStatus::PAY_STATUS_REFUND),
                   ];
               }
               break;

           case 'card_consume':
               foreach ($ranges as $range => $range_date){
                   $result[] = [
                       'range' => $range,
                       'total' => $count_service->collectionTotalConsume($mch_id, $range_date[0], $range_date[1]),
                   ];
               }
               break;
           default:
               foreach ($ranges as $range => $range_date){
                   $result[] = [
                       'range' => $range,
                       'total' => $count_service->collectionTotal($mch_id, $range_date[0], $range_date[1], PayOrderStatus::PAY_STATUS_SUCCES),
                   ];
               }
               break;
       }

       return Response::success('', [
           'ranges' => $result,
           'cat'    => $cat
       ]);
   }


}