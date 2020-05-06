<?php namespace App\Service\Pay;

use App\Exceptions\PayPaymentException;
use App\Models\CardCodeModel;
use App\Service\Auth\MemberCode;
use App\Service\Member\Member;
use Illuminate\Support\Facades\Redis;

class PayCode
{

    private $key      = 'mt_2018_paycode_order';
    private $expires  = 600;
    private $matchers = [];

    public function __construct()
    {
        $this->matchers = [
            new PayCodeBalance()
        ];
    }

    public function codeOrderRelation($code, $order_id)
    {
        $this->save($code, $order_id);
    }

    public function deleteRalation($code)
    {
        $name = $this->getCacheName($code);

        Redis::del($name);
    }

    public function codeToCardAndCode($auth_code)
    {
        $member_id = $this->codeToMemberId($auth_code);

        $member_service = new Member();
        $member_row     = $member_service->getMemberAndCheck($member_id);
        $code_model     = new CardCodeModel();

        $m_card_id   = $code_model->getCardIdByCodeId($member_row->member_card_code_id);
        $m_card_code = $member_row->member_card_code;

        return [$m_card_id, $m_card_code];
    }

    public function codeToMemberId($auth_code)
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->isMe($auth_code)) {
                $auth_code = $matcher->removePrefix($auth_code);
                break;
            }
        }

        $member_code = new MemberCode();

        $member_id = $member_code->decode($auth_code);

        return $member_id;
    }

    public function getOrderIdByCode($code)
    {
        $name = self::getCacheName($code);

        $id = self::get($name);

        if (!$id) {
            throw new PayPaymentException('待扫码');
        }

        return $id;
    }

    private function getCacheName($number)
    {
        return $this->key . $number;
    }

    private function get($name)
    {
        return Redis::get($name);
    }

    private function save($number, $code)
    {
        $name = $this->getCacheName($number);

        Redis::set($name, $code);

        Redis::expire($name, $this->expires);
    }
}

