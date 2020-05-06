<?php namespace App\Models;

use App\DataTypes\FictitiousCardTypes;
use App\Models\Traits\DynamicWhereTrait;
use App\Service\Export\Contracts\ExportSupportInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Libs\Time;

class FictitiousCardCodeModel extends Model implements ExportSupportInterface
{

    use DynamicWhereTrait;

    protected $table = 'fictitious_card_code';

    protected $fillable = [
        'card_id', 'password', 'code_no', 'status'
    ];

    public $timestamps = false;

    public function getByPwd($code, $pwd)
    {
        return $this->where('code_no', $code)
            ->where('password', $pwd)->first();
    }

    public function toUsed($id)
    {
        return $this->where('id', $id)->update([
            'status' => FictitiousCardTypes::code_status_used
        ]);
    }

    public function createNew($data)
    {
        return $this->create($data);
    }

    public function deleteNotUseCode($len)
    {
        return $this->where('status', FictitiousCardTypes::code_status_grant)->limit($len)->delete();
    }

    private function scopeFilter($model, $request)
    {
        $model = $this->dynamicAnyWhere($model, [
            [
                'status',
                'status'
            ],
            [
                'card_id',
                'card_id'
            ]
        ], $request);

        return $model;
    }

    public function manageLimit($limit, Collection $request)
    {
        $model = $this->select('*');
        $model = $this->scopeFilter($model, $request);
        return $model->paginate($limit);
    }

    public function exportByIds($ids, $request)
    {
        return $this->select('code_no', 'password', 'status')
            ->whereIn('id', $ids)
            ->get();
    }

    public function filterNoLimit($request)
    {
        $request = new Collection($request);
        $model   = $this->select('code_no', 'password', 'status');
        $model   = $this->scopeFilter($model, $request);

        return $model->get();
    }

    public function cells($list)
    {
        $result = [];
        $header = [
            "卡号", "密码", '状态'
        ];

        foreach ($list as $row) {
            $result[] = [
                $row['code_no'],
                $row['password'],
                FictitiousCardTypes::statusToName($row['status']),
            ];
        }

        return [$header, $result];
    }

}

