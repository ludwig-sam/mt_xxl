<?php namespace App\Models;


use App\Models\Traits\CallTableAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class MchModel extends Model
{

    use SoftDeletes;
    use CallTableAble;

    protected $table = 'mch';


    protected $fillable = [
        'name', 'manager', 'manager_phone',
        'logo', 'description', 'mch_category_id',
        'banner', 'transaction_number', 'is_hot',
        'sort', 'is_stop', 'address', 'refund_pwd'
    ];

    protected $dates  = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public function setRefundPwdAttribute($val)
    {
        if ($val) {
            $this->attributes['refund_pwd'] = md5($val);
        }
    }

    public function setBannerAttribute($banner)
    {
        $this->attributes['banner'] = json_encode($banner);
    }

    public function getBannerAttribute()
    {
        return json_decode($this->attributes['banner']);
    }

    public function incrementTranscationNumber($id)
    {
        return $this->where('id', (int)$id)->increment('transaction_number');
    }


    public function listNoLimit($request)
    {
        return $this->filterQuery(new Collection($request))->get();
    }

    public function filterQuery(Collection $request)
    {
        return $this->from($this->table . ' as mch')
            ->leftJoin('mch_category as m_c', 'mch.mch_category_id', '=', 'm_c.id')
            ->select('mch.*', 'm_c.name as mch_category_name')
            ->when($request->get('mch_category_name'), function ($query) use ($request) {
                return $query->where('m_c.name', 'like', '%' . $request->get('mch_category_name') . '%');
            })->when($request->get('cid'), function ($query) use ($request) {
                return $query->where('mch.mch_category_id', $request->get('cid'));
            })->when($request->get('name'), function ($query) use ($request) {
                return $query->where('mch.name', 'like', '%' . $request->get('name') . '%');
            })->orderBy('sort', 'asc')->orderBy('id', 'desc');
    }

    public function exportFromLimit($ids, $fields)
    {

        return $this->from($this->table . ' as mch')
            ->select($fields)->leftJoin('mch_category as m_c', 'mch.mch_category_id', '=', 'm_c.id')
            ->whereIn('mch.id', $ids)
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }
}
