<?php namespace App\Service\Member;


use App\Exceptions\MemberException;
use App\Models\MemberAccountConfigModel;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;
use Providers\Curd\CurdServiceTrait;


class MemberAccountConfigService extends Service
{

    use CurdServiceTrait;

    private $user;

    public function model():MemberAccountConfigModel
    {
        return $this->newSingle(MemberAccountConfigModel::class);
    }

    public function __construct(UserAbstraict $user)
    {
        $this->user = $user;
        $this->row  = $this->model()->getByMemberId($user->getId());
    }

    public function getConfig()
    {
        $data = $this->row ? $this->row->toArray() : [];

        return new Collection($data);
    }

    public function update($row, $req)
    {
        $data              = toArray($req);
        $row               = $row ?: $this->row;
        $data['member_id'] = $this->user->getId();

        if ($row) {
            return $row->update($data);
        }

        return $this->create($data);
    }

    public function getPayPwd()
    {
        return $this->getConfig()->get('pay_password');
    }

    public function hasPayPwd()
    {
        return $this->getPayPwd();
    }

    public function checkOldPwd($old_pwd)
    {
        if (!$this->hasPayPwd()) return;

        if (md5($old_pwd) != $this->getPayPwd()) {
            throw new MemberException('原密码错误');
        }
    }

}