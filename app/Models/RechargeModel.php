<?php namespace App\Models;

use App\DataTypes\RecharegeStatus;
use App\Models\Traits\CallTableAble;
use App\Models\Traits\DynamicWhereTrait;
use App\Service\Export\Contracts\ExportSupportInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RechargeModel extends Model implements ExportSupportInterface
{

    use DynamicWhereTrait;
    use CallTableAble;

    protected $table = 'recharge';


    protected $fillable = [
        'member_id', 'order_no', 'payment_id', 'payment_name', 'amount', 'status', 'trade_no',
        'payment_at', 'expired_at', 'openid', 'card_no'
    ];

    private function scopeFilter(&$model, Collection $request)
    {
        if (!$request->get('status')) $request->offsetSet('status', RecharegeStatus::success);

        $this->dynamicAnyWhere($model, [
            [
                'status',
                'status',
            ],
            [
                'nickname',
                'like',
                'nickname'
            ],
            [
                'order_no',
                'order_no'
            ],
            [
                'name',
                'like',
                'name'
            ]
        ], $request);

        $this->dateRange($model, $request, 'begin_at', 'end_at', self::f('created_at'));

        return $model;
    }

    public function manageLimit($limit, Collection $request)
    {
        return $this->scopeListQuery($request)
            ->paginate($limit);
    }

    private function scopeListQuery(Collection $request)
    {
        $fields = [
            self::f('id'),
            self::f('order_no'),
            self::f('payment_name'),
            self::f('amount'),
            self::f('created_at'),
            self::f('status'),
            MemberModel::f('nickname'),
            MemberModel::f('name')
        ];

        $model = $this->select($fields);

        $this->scopeFilter($model, $request);

        return $model
            ->join(MemberModel::table(), MemberModel::f('id'), 'member_id')
            ->orderBy(self::f('id'), 'DESC');
    }

    public function total(Collection $request)
    {
        $model = $this;

        $this->scopeFilter($model, $request);

        return $model
            ->join(MemberModel::table(), MemberModel::f('id'), 'member_id')
            ->sum('amount');
    }

    public function filterNoLimit($request)
    {
        return $this
            ->scopeListQuery($request)
            ->get();
    }

    public function exportByIds($ids, $request)
    {
        $request = new Collection([]);

        return $this->scopeListQuery($request)
            ->whereIn(self::f('id'), $ids)
            ->get();
    }

    public function cells($list)
    {
        $result = [];
        $header = [
            "订单号", "支付名称", "金额", "昵称", "姓名", "时间"
        ];

        foreach ($list as $row) {
            $result[] = [
                $row['order_no'],
                $row['payment_name'],
                $row['amount'],
                $row['nickname'],
                $row['name'],
                $row['created_at']
            ];
        }

        return [$header, $result];
    }
}

