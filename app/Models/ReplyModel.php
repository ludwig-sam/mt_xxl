<?php namespace App\Models;


use Libs\Time;
use Illuminate\Database\Eloquent\Model;

class ReplyModel extends Model {

    protected $table = 'wechat_reply';

    protected $fillable = [
        'event_name','event_key','is_stop'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function hasMaterial()
    {
        return $this->hasMany(ReplyMaterialModel::class, 'reply_id', 'id');
    }

    public function getReplyAndMaterial($where){
        return $this->from($this->table . ' as r')
            ->join((new ReplyMaterialModel())->getTable() . ' as rm', 'r.id', '=', 'rm.reply_id')
            ->join('wechat_material as m', 'rm.material_id', '=', 'm.id')
            ->where($where)
            ->get();
    }

    public function material(){
        return $this->belongsTo(MaterialModel::class, 'material_id', 'id');
    }

    public function replyMaterial(){
        return $this->hasOne(ReplyMaterialModel::class, 'reply_id', 'id');
    }

    public function create($reply, $materials)
    {
        $row = $this->fill($reply, $materials)->save();

        foreach ($materials as $material){
            $this->hasMaterial()->create($material);
        }

        return $row;
    }

    public function edit($id, $materials)
    {
        $row = $this->find($id);

        $row->hasMaterial()->delete();

        foreach ($materials as $material){
            $row->hasMaterial()->create($material);
        }

        return $row->update(['updated_at' => Time::date()]);
    }

    public function getReplyDetail($where)
    {
        return parent::with('hasMaterial')->where($where)->first();
    }
}

