<?php namespace App\Service\Member;

use App\Exceptions\MemberException;
use App\Service\Users\MemberUser;
use Libs\Arr;
use Libs\Time;
use App\Models\MemberInterestModel;
use App\Models\MemberModel;
use App\Service\Export\Contracts\ExportSupportInterface;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;


class Member extends Service
{

    public function becomeMember(UserAbstraict $user, $memberData)
    {

        $memberActor = new MemberActor();

        $id = $memberActor->becomeMember($memberData);

        $this->use($memberActor->result);

        $user->setId($id);

        return $this->result->isSuccess();
    }

    public function checkBalance(UserAbstraict $user, $amount)
    {
        if ($amount > $user->getAttribute('balance')) {
            throw new MemberException('余额不足');
        }
    }

    public function subscribe($openid, $is_subscribe = 0)
    {
        $member_model = new MemberModel();

        $row = $member_model->getByOpenid($openid);

        if (!$row) {
            throw new MemberException("会员不存在", compact('openid'));
        }

        $row->update([
            'is_subscribe' => $is_subscribe
        ]);

        return $row;
    }

    public function loginById($id)
    {
        $member_model    = new MemberModel();
        $user            = $member_model->getById($id);
        $member_instance = MemberUser::getInstance();

        $member_instance->init($user);

        return $member_instance;
    }

    public function getMemberAndCheck($member_id)
    {
        $member_model = new MemberModel();

        $row = $member_model->find($member_id);

        if (!$row) {
            throw new MemberException("会员不存在", compact('member_id'));
        }

        return $row;
    }

    public function addInterest($memberId, Array $interests)
    {

        $memberInterestModel = new MemberInterestModel();

        $memberInterestModel->where("member_id", $memberId)->delete();

        $interests = Arr::format($interests, 'intval');
        $interests = Arr::filter($interests, [0]);

        foreach ($interests as $interest) {
            $memberInterestModel->create(['member_id' => $memberId, 'mch_category_id' => $interest]);
        }

        return true;
    }

    public function saveMemberCode($member_id, $code, $code_id)
    {
        $model = new MemberModel();
        $row   = $model->find($member_id);

        if (!$row) {
            throw new MemberException("会员不存在");
        }

        if ($row->member_card_code) {
            return true;
        }

        $row->member_card_code_id = (int)$code_id;
        $row->member_card_code    = $code;

        return $row->save();
    }

    public function getMemberByCode($code)
    {
        $model  = new MemberModel();
        $member = $model->where("member_card_code", $code)->first();
        if (!$member) {
            throw new MemberException("会员码错误", MemberException::invalid_member_code);
        }
        return $member;
    }

    public static function pubUserParam(Collection $data)
    {
        return new Collection([
            "is_subscribe"   => $data->get('subscribe', 0),
            "openid"         => $data->get('openid'),
            "nickname"       => $data->get('nickname'),
            'sex'            => $data->get('sex') == 1 ? "boy" : "girl",
            "city"           => $data->get('city'),
            "province"       => $data->get('province'),
            "country"        => $data->get('country'),
            "headurl"        => $data->get('headimgurl'),
            "subscribe_time" => $data->get('subscribe_time'),
            "unionid"        => $data->get('unionid'),
        ]);
    }

    public static function miniUserParama(Collection $data)
    {
        return new Collection([
            "mini_openid" => $data->get('openId'),
            "nickname"    => $data->get('nickName'),
            'sex'         => $data->get('gender') == 1 ? "boy" : "girl",
            "city"        => $data->get('city'),
            "province"    => $data->get('province'),
            "country"     => $data->get('country'),
            "headurl"     => $data->get('avatarUrl'),
            "unionid"     => $data->get('unionId'),
        ]);
    }

    public function miniLogin(Collection $data)
    {
        return $this->doLogin(self::miniUserParama($data)->toArray());
    }

    public function pubLogin(Collection $data)
    {
        return $this->doLogin(self::pubUserParam($data)->toArray());
    }

    private function doLogin($save)
    {

        $sexs             = [
            "boy"  => 1,
            "girl" => 0
        ];
        $save['password'] = 0;
        $save['sex']      = $sexs[$save['sex']];

        $memberModel = new MemberModel();
        $member      = $memberModel->where('unionid', $save['unionid'])->first();

        if (!$member) {
            $save['last_login_at'] = Time::date();

            if ($memberModel->fill($save)->save()) {
                return true;
            }

            throw new MemberException("添加用户失败", MemberException::member_create_fail);
        }

        $update = [];

        foreach ($save as $k => $v) {
            if (!$member->$k && !is_null($v)) {
                $update[$k] = $v;
            }
        }

        $update['last_login_at'] = Time::date();

        return $member->update($update);
    }

    public function registeReward(UserAbstraict $user, $card_id)
    {
        $reward_service = new RegisteReward($user);

        $config = $reward_service->getConfig($card_id);

        if (!$config) return false;

        return $reward_service->add($config->reward_card_id);
    }
}