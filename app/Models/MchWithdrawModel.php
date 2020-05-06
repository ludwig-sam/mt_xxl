<?php namespace App\Models;


use App\DataTypes\WithdrawTypes;
use App\Models\Traits\CallTableAble;
use App\Models\Traits\DynamicWhereTrait;
use App\Service\Export\Contracts\ExportSupportInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MchWithdrawModel extends Model implements ExportSupportInterface
{

    use DynamicWhereTrait;
    use CallTableAble;

    protected $table = 'mch_withdraw_apply';

    protected $fillable = [
        'apply_money', 'dispose_money', 'order_no', 'bank_order_no', 'poundage', 'status', 'remark',
        'apply_user_id', 'oprator_user_id', 'mch_id', 'mobile', 'card_number', 'bank_name',
        'account_name', 'created_at', 'updated_at', 'initial_bank'
    ];

    private function scopeWithMch()
    {
        $fields = [
            self::f('id'),
            self::f('order_no'),
            self::f('apply_money'),
            self::f('poundage'),
            self::f('dispose_money'),
            self::f('created_at'),
            self::f('status'),
            MchModel::f('name', 'mch_name')
        ];

        return $this->join(MchModel::table(), MchModel::f('id'), 'mch_id')
            ->select($fields);
    }

    private function scopeFilter($model, Collection $request)
    {
        $model = $this->dynamicAnyWhere($model, [
            [
                'mch_id',
                'mch_id'
            ],
            [
                'status',
                'status'
            ],
            [
                'order_no',
                'like',
                'order_no'
            ]
        ], $request);

        $this->dateRange($model, $request, 'begin_at', 'end_at', self::f('created_at'));

        return $model;
    }

    public function mchLimit($limit, Collection $request)
    {
        $model = $this->select('*');
        $model = $this->scopeFilter($model, $request);

        return $model->orderBy('id', 'desc')->paginate($limit);
    }

    public function manageLimit($limit, Collection $request)
    {
        $model = $this->scopeWithMch();
        $model = $this->scopeFilter($model, $request);

        return $model->orderBy(self::f('id'), 'desc')->paginate($limit);
    }

    public function disposTotal()
    {
        return $this->where('status', WithdrawTypes::status_success)
            ->sum('dispose_money');
    }

    public function disposTotalWithMch($mch_id)
    {
        return $this->where('status', WithdrawTypes::status_success)
            ->where('mch_id', $mch_id)
            ->sum('dispose_money');
    }

    public function pendingTotalWithMch($mch_id)
    {
        return $this->where('status', WithdrawTypes::status_peding)
            ->where('mch_id', $mch_id)
            ->sum('dispose_money');
    }

    public function pendingTotal()
    {
        return $this->where('status', WithdrawTypes::status_peding)
            ->sum('dispose_money');
    }

    public function total()
    {
        return $this->sum('apply_money');
    }

    public function cells($list)
    {

        $result = [];
        $header = [
            "订单号", "申请金额", "手续费", "到账金额", "状态", "商户", "申请时间"
        ];

        foreach ($list as $row) {
            $result[] = [
                $row['order_no'],
                $row['apply_money'],
                $row['poundage'],
                $row['dispose_money'],
                $row['created_at'],
                WithdrawTypes::statusToName($row['status']),
                $row['mch_name'],
            ];
        }

        return [$header, $result];
    }

    public function exportByIds($ids, $request)
    {
        $model = $this->scopeWithMch();
        $model = $this->scopeFilter($model, $request);

        return $model->whereIn(self::f('id'), $ids)->get();
    }

    public function filterNoLimit($request)
    {
        $model = $this->scopeWithMch();
        $model = $this->scopeFilter($model, $request);

        return $model->get();
    }
}

