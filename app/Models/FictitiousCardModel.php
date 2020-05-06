<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FictitiousCardModel extends Model
{

    protected $table = 'fictitious_card';

    protected $fillable = [
        'batch_order_no', 'card_name', 'amount', 'quantity', 'stock', 'status', 'date_type', 'start_at', 'end_at'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function limit($limit, Collection $request)
    {
        $model = $this->orderBy('id', 'desc');

        return $model->paginate($limit);
    }

    public function decStock($id)
    {
        return $this->where('id', $id)->decrement('stock');
    }

}

