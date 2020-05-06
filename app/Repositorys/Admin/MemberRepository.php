<?php namespace App\Repositorys\Admin;


use App\DataTypes\PayOrderStatus;
use App\Service\Export\Contracts\ExportSupportInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\MemberModel;
use App\Models\MemberInterestModel;

class MemberRepository implements ExportSupportInterface
{

    private $model;

    public function __construct(MemberModel $model)
    {
        $this->model = $model;
    }

    public function show($id)
    {
        $member            = $this->model->select('nickname', 'mobile', 'headurl', 'person_name', 'birth_day', 'sex', 'id_card', 'profession', 'level', 'point', 'balance', 'created_at')->find($id);
        $first_pay_date    = DB::table('pay_order')->select('created_at')->where('member_id', $id)->orderBy('created_at')->pluck('created_at')->first();
        $last_pay_date     = DB::table('pay_order')->select('created_at')->where('member_id', $id)->orderBy('created_at', 'desc')->pluck('created_at')->first();
        $mch_category_name = DB::table('member_interest')
            ->leftJoin('mch_category', 'mch_category_id', 'mch_category.id')
            ->select('mch_category.name as mch_category_name')
            ->where('member_id', $id)
            ->get();
        $interest          = array();
        foreach ($mch_category_name as $item) {
            array_push($interest, $item->mch_category_name);
        }

        $member['interest']       = $interest;
        $member['first_pay_date'] = $first_pay_date;
        $member['last_pay_date']  = $last_pay_date;
        return $member;
    }

    public function update($id, $data)
    {
        $interests = [];

        MemberInterestModel::where('member_id', $id)->delete();

        if (isset($data['interest'])) {
            $interests = $data['interest'];
            unset($data['interest']);
        }

        $member = MemberModel::find($id);
        $member->fill($data)->save();

        if ($interests) {
            $interests = array_unique($interests);
            foreach ($interests as $interest) {
                $temp                  = new MemberInterestModel();
                $temp->member_id       = $id;
                $temp->mch_category_id = $interest;
                $temp->save();
            }
        }

        return true;
    }

    public function limit($limit, $request)
    {

        $request = new Collection($request);

        return $this->filterQuery($request)
            ->paginate($limit);
    }

    public function listNoLimit($request)
    {
        $request = new Collection($request);

        return $this->filterQuery($request)
            ->get();
    }

    public function openidNoLimit($request)
    {

        $request = new Collection($request);

        return $this->filterQuery($request)
            ->select('openid')
            ->get();
    }

    public function filterQuery(Collection $request)
    {
        if ($consuming_ability = $request->get('consuming_ability')) {
            switch ($consuming_ability) {
                case 'TEN':
                    $request['min_consum'] = 10000;
                    $request['max_consum'] = null;
                    break;
                case 'HUNDRED':
                    $request['min_consum'] = 100000;
                    $request['max_consum'] = null;
                    break;
                default:
                    break;
            }
        }

        return $this->model->leftJoin('pay_order', 'member.id', '=', 'pay_order.member_id')
            ->leftJoin('mch', 'pay_order.mch_id', '=', 'mch.id')
            ->when($request->get('interest_id'), function ($query) use ($request) {
                return $query->leftJoin('member_interest', 'member_interest.member_id', '=', 'member.id');
            })
            ->select('member.id as member_id', DB::raw('COUNT(pay_order.id) as transaction_count'), DB::raw('ifNull(SUM(amount),0) as transaction_total '), 'member.name', 'member.headurl', 'member.person_name', 'member.nickname', 'member.level', 'member.mobile', 'member.point', 'member.balance', 'member.birth_day', 'member.created_at', 'is_subscribe')
            ->when($request->get('mch_id'), function ($query) use ($request) {
                return $query->where('pay_order.mch_id', $request['mch_id']);
            })
            ->when($request->get('mch_category_id'), function ($query) use ($request) {
                return $query->where('mch.mch_category_id', $request['mch_category_id']);
            })
            ->when($request->get('interest_id'), function ($query) use ($request) {
                return $query->where('member_interest.mch_category_id', $request['interest_id']);
            })
            ->when($request->get('profession'), function ($query) use ($request) {
                return $query->where('member.profession', $request['profession']);
            })
            ->when($request->get('level'), function ($query) use ($request) {
                return $query->where('member.level', $request['level']);
            })
            ->when($request->get('sex'), function ($query) use ($request) {
                return $query->where('member.sex', $request['sex']);
            })
            ->when($request->get('person_name'), function ($query) use ($request) {
                return $query->where('member.person_name', 'like', '%' . $request['person_name'] . '%');
            })
            ->when($request->get('nickname'), function ($query) use ($request) {
                return $query->where('member.nickname', 'like', '%' . $request['nickname'] . '%');
            })
            ->when($request->get('min_birth_day'), function ($query) use ($request) {
                return $query->where('member.birth_day', '>=', $request['min_birth_day']);
            })
            ->when($request->get('max_birth_day'), function ($query) use ($request) {
                return $query->where('member.birth_day', '<=', $request['max_birth_day']);
            })
            ->when($request->get('pay_begin_time'), function ($query) use ($request) {
                return $query->where('pay_order.created_at', '>=', $request['pay_begin_time'])
                    ->where('pay_order.status', PayOrderStatus::PAY_STATUS_SUCCES);
            })->when($request->get('pay_end_time'), function ($query) use ($request) {
                return $query->where('pay_order.created_at', '<=', $request['pay_end_time'])
                    ->where('pay_order.status', PayOrderStatus::PAY_STATUS_SUCCES);
            })
            ->when($request->get('sort_by'), function ($query) use ($request) {
                return $query->orderBy($request['sort_by'], $request['sort_rule']);
            })
            ->groupBy('member.id')
            ->when($request->get('min_consum') !== null && $request->get('min_consum') != "", function ($query) use ($request) {
                return $query->havingRaw("ifNull(SUM(amount),0) >= {$request['min_consum']}");
            })
            ->when($request->get('max_consum') !== null && $request->get('min_consum') != "", function ($query) use ($request) {
                return $query->havingRaw("ifNull(SUM(amount),0) <= {$request['max_consum']}");
            })
            ->when($request->get('consuming_ability') == "NONE", function ($query) use ($request) {
                return $query->havingRaw("COUNT(pay_order.id) = 0");
            })
            ->when($request->get('consuming_ability') == "CONSUMED", function ($query) use ($request) {
                return $query->havingRaw("COUNT(pay_order.id) >= 1");
            })
            ->orderBy('member.id', 'desc');
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function updateOne($data)
    {
        $member = MemberModel::find($data['id']);
        return $member->fill($data)->save();
    }

    public function filterNoLimit($request)
    {
        $request = new Collection($request);
        return $this->listNoLimit($request);
    }

    public function exportByIds($ids, $request)
    {
        $request = new Collection($request);

        $fields = ['member.id as member_id', 'member.nickname', 'member.headurl', 'member.person_name', 'member.level', 'member.mobile', 'member.point', 'member.balance'];

        return $this->model->exportFromLimit($ids, $request->get('order_by'), $request->get('sort'), $fields);
    }

    public function cells($list)
    {
        $result = [];
        $header = [
            "ID", "微信昵称", "姓名", "会员等级", "手机", "积分", "交易总额", "买单数", "余额"
        ];

        foreach ($list as $row) {
            $result[] = [
                $row['member_id'],
                $row['nickname'],
                $row['person_name'],
                $row['level'],
                $row['mobile'] . ' ',
                $row['point'],
                (float)$row['transaction_total'],
                (int)$row['transaction_count'],
                $row['balance'],
            ];
        }

        return [$header, $result];
    }
}