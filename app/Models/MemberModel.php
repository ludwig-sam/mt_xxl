<?php namespace App\Models;

use App\DataTypes\PayOrderStatus;
use App\Models\Traits\CallTableAble;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MemberModel extends Authenticatable
{
    use Notifiable;
    use CallTableAble;

    protected $table = 'member';


    protected $fillable = [
        'name', 'password', 'mobile', 'mini_openid', 'headurl', 'person_name', 'id_card', 'balance', 'profession', 'level', 'point', 'birth_day',
        'nickname', 'unionid', 'openid', 'last_login_at', "member_card_code_id", "member_card_code",
        'is_member', 'is_subscribe', 'sex'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function getByUnionId($unionId)
    {
        return $this->where('unionid', $unionId)->first();
    }

    public function getByOpenid($openid)
    {
        return $this->where('openid', $openid)->first();
    }

    public function getIdByOpenid($openid)
    {
        return $this->where('openid', $openid)->value('id');
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function exportFromLimit($ids, $sortBy, $sort, $fields)
    {

        $fields[] = \DB::raw('COUNT(pay.id) as transaction_count');
        $fields[] = \DB::raw('SUM(amount) as transaction_total ');

        return $this->from($this->table . ' as member')
            ->leftJoin((new PayOrderModel())->getTable() . ' as pay', function ($join) {
                $join->on('member.id', '=', 'pay.member_id')->where('pay.status', PayOrderStatus::PAY_STATUS_SUCCES);
            })
            ->when(in_array($sortBy, ['member_id', 'created_at']), function ($query) use ($sortBy, $sort) {

                if (!in_array($sort, ['desc', 'asc'])) return $query;

                return $query->orderBy($sortBy, $sort);
            })
            ->whereIn('member.id', $ids)
            ->groupBy('member.id')
            ->select($fields)
            ->get();
    }

    public function getIntrest($memberId)
    {
        return $this->from((new MemberInterestModel())->getTable() . ' as i')
            ->leftJoin((new MchCategoryModel())->getTable() . ' as c', 'c.id', '=', 'i.mch_category_id')
            ->select('c.name', 'c.id')
            ->where('i.member_id', $memberId)
            ->get();
    }

    public function getProfessionName($id)
    {
        return (new ProfessionModel())->find($id);
    }

    public function getProfessionMakeUpCount()
    {
        return $this->from($this->table . ' as m')
            ->leftJoin((new ProfessionModel())->getTable() . ' as p', 'p.id', '=', 'm.profession')
            ->select(\DB::raw("count('m.*') as count"), 'p.name', 'p.id')
            ->groupBy('p.id')
            ->get();
    }

}

