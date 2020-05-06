<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/6/30
 * Time: 下午4:52
 */

namespace App\Service\Account;


use App\Exceptions\MemberException;
use App\Models\MemberAccountLogModel;
use App\Service\MemberLevel\MemberLevelUpgrade;
use App\Service\Users\Contracts\UserAbstraict;

class Account
{

    const event_name_give     = 'GIVE';
    const event_name_consume  = 'CONSUME';
    const event_name_reback   = 'REBACK';
    const scene_name_pay      = 'PAY';
    const scene_name_exchange = 'EXCHANGE';
    const scene_name_recharge = 'RECHARGE';

    const name_point   = 'POINT';
    const name_balance = 'BALANCE';
    const name_exp     = 'EXP';

    private $sceneName;
    private $eventName;
    private $user;

    public function __construct(UserAbstraict &$userAbstraict, $eventName, $sceneName)
    {
        $this->eventName = $eventName;
        $this->sceneName = $sceneName;
        $this->user      = &$userAbstraict;
    }

    public function pointAdd($value, Array $config = [])
    {
        $value          = abs($value);
        $config['name'] = self::name_point;

        return $this->write($value, $config, 'point', $value);
    }

    public function pointReduce($value, Array $config = [])
    {
        $value          = -abs($value);
        $config['name'] = self::name_point;

        return $this->write($value, $config, 'point', $value);
    }

    public function balanceAdd($value, Array $config = [])
    {
        $value          = abs($value);
        $config['name'] = self::name_balance;

        return $this->write($value, $config, 'balance', $value);
    }

    public function balanceReduce($value, Array $config = [])
    {
        $value          = -abs($value);
        $config['name'] = self::name_balance;

        return $this->write($value, $config, 'balance', $value);
    }

    public function expAdd($value, Array $config = [])
    {
        $value          = abs($value);
        $config['name'] = self::name_exp;

        $this->write($value, $config, 'exp', $value);

        $member_level_up_service = new MemberLevelUpgrade($this->user);

        $member_level_up_service->update();

        return true;
    }

    public function expReduce($value, Array $config = [])
    {
        $value          = -abs($value);
        $config['name'] = self::name_exp;

        $this->write($value, $config, 'exp', $value);

        $member_level_up_service = new MemberLevelUpgrade($this->user);

        $member_level_up_service->update();

        return true;
    }

    private function write($value, Array $config, $memberField, $memberValue)
    {
        $final = $this->user->model()->$memberField + $memberValue;

        $this->chAccount([$memberField => $final]);

        $config['total'] = $final;

        return $this->log($value, $config);
    }

    private function log($value, $config)
    {

        $config['value']     = $value;
        $config['member_id'] = $this->user->getId();

        if ($this->eventName) {
            $config['event_name'] = $this->eventName;
        }

        if ($this->sceneName) {
            $config['scene_name'] = $this->sceneName;
        }

        $model = new MemberAccountLogModel();

        if (!$model->fill($config)->save()) {
            throw new MemberException("账户日志添加失败");
        }

        return true;
    }

    private function chAccount($data)
    {

        if (!$this->user->model()->where("id", $this->user->getId())->update($data)) {
            throw new MemberException("账号更新失败");
        }

        foreach ($data as $name => $value) {
            $this->user->setAttribute($name, $value);
        }

        return true;
    }

}