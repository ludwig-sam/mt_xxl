<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/17
 * Time: 下午8:55
 */

namespace App\Service\Count;


use Libs\Time;
use App\Models\CardModel;
use App\Models\MchModel;
use App\Models\MemberAccountLogModel;
use App\Models\MemberModel;
use App\Models\PayOrderModel;
use App\DataTypes\PayOrderStatus;
use App\Service\Account\Account;
use Illuminate\Support\Collection;

class Count
{

    private $request;

    public function __construct(Collection $collection)
    {
        $this->request = $collection;
    }

    private function getInputSDate()
    {
        return Time::dateBefore($this->request->get('before_days', 0));
    }

    private function getSDate()
    {
        return Time::date($this->getInputSDate(), 'Y-m-d') . ' 00:00:00';
    }

    private function getEDate()
    {
        if($this->getInputSDate() == 0){
            return Time::date();
        }

        return Time::date(time(), 'Y-m-d') . ' 00:00:00';
    }

    public function getMchCount()
    {
        $mch_model    = new MchModel();

        return $mch_model->count();
    }

    public function getProfessionMakeUpCount()
    {
        $member_model = new MemberModel();

        $list = $member_model->getProfessionMakeUpCount()->toArray();

        $this->countProfessionPercent($list);

        return $list;
    }

    private function countProfessionPercent(&$list)
    {
        $count = array_sum(array_column($list, 'count'));

        $percent = 0;

        foreach ($list as $k => &$row){
            $row['percent'] = floor($row['count'] / $count * 100);

            if($k == count($list) - 1){
                $row['percent'] = 100 - $percent;
            }

            $percent += $row['percent'];
        }

    }

    public function getMemberCount()
    {
        $member_model = new MemberModel();

        return $member_model->count();
    }

    public function getCardCount()
    {
        $card_model   = new CardModel();

        return $card_model->sum('total_quantity');
    }

    public function getPayConsumeCardCount()
    {
        $card_model   = new PayOrderModel();

        return $card_model->getConsumeCardCount($this->getSDate(), $this->getEDate());
    }

    public function getPayAmountTotal()
    {
        $pay_model    = new PayOrderModel();
        return $pay_model->whereIn('status', [PayOrderStatus::PAY_STATUS_SUCCES, PayOrderStatus::PAY_STATUS_REFUND])
            ->where('created_at', '>', $this->getSDate())
            ->where('created_at', '<=', $this->getEDate())
            ->sum('amount');
    }

    public function getRefundAmountTotal()
    {
        $pay_model    = new PayOrderModel();
        return $pay_model->where('created_at', '>', $this->getSDate())
            ->where('created_at', '<=', $this->getEDate())
            ->sum('refund_amount');
    }

    public function getPointHistoryTotal()
    {
        $account_log_model = new MemberAccountLogModel();

        return (int)$account_log_model->where('name', Account::name_point)->where('event_name', Account::event_name_give)->sum('value');
    }

    public function getUsedPointTotal()
    {
        $account_log_model = new MemberAccountLogModel();

        return abs($account_log_model->where('name', Account::name_point)->where('value', '<' , 0)->sum('value'));
    }

    public function activityMemberCount($activity_days)
    {
        $member_model = new MemberModel();

        $sdate        = Time::dateBefore($activity_days);
        $sdate        = Time::date($sdate);

        return $member_model->where('last_login_at', '>=', $sdate)->count();
    }

    public function lostMemberCount($lost_days)
    {
        $member_model = new MemberModel();

        $sdate        = Time::dateBefore($lost_days);
        $sdate        = Time::date($sdate);

        return $member_model->where('last_login_at', '<', $sdate)->count();
    }

    private function payOrderModel()
    {
        static $pay_model ;

        if(!$pay_model)$pay_model = new PayOrderModel();

        return $pay_model;
    }

    public function collectionTotal($mch_id, $s_date, $e_date, $status)
    {
        return $this->payOrderModel()->timeRangePayColelctionTotal($mch_id, $s_date, $e_date, $status);
    }

    public function collectionTotalConsume($mch_id, $s_date, $e_date)
    {
        return $this->payOrderModel()->timeRangeCollectionTotalConsume($mch_id, $s_date, $e_date);
    }

    public function parseTotalRanges()
    {
        $ranges = $this->request->get('ranges');

        $result = [];

        foreach ($ranges as $range){
            $tmp    = explode('~', $range);
            $s_date = $tmp[0];
            $e_date = $tmp[1];

            $result[$range] = [$s_date, $e_date];
        }

        return $result;
    }

}