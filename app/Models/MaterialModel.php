<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MaterialModel extends Model {

    protected $table = 'wechat_material';

    protected $fillable = [
        'type','updated_at','deleted_at','mp_media_id','title'
    ];

    public static function getContentTable($materialType)
    {
        return self::model($materialType)->getTable();
    }

    public static function model($type) : Model
    {
        $class = __NAMESPACE__ . '\\Materials\\Material' . Str::studly($type) . 'Model';
        return new $class();
    }

    public function getCompleteInfo($materialId, $materialType){
        return $this->from($this->table . ' as m')
            ->join(self::getContentTable($materialType) . ' as mc', 'm.id' , '=', 'mc.material_id')
            ->select('m.type', 'm.title', 'mc.*', 'm.id')
            ->where('m.id', '=', $materialId)
            ->first();
    }

    public function add($type, $content, $mdata = [])
    {
        $mdata['type'] = $type;

        $row  = $this->create($mdata);

        $content['material_id'] = $row->id;

        return self::model($type)->create($content);
    }

    public function edit($id, $content, $mdata = [])
    {
        $material_row = $this->find($id);
        $affetch      = $material_row->update($mdata);

        $content_model= self::model($material_row->type);

        $content_row  = $content_model->where('material_id', $id)->first();

        if($content_row){
            $content_row->update($content);
        }else{
            $content['material_id'] = $id;
            $content_model->create($content);
        }

        return $affetch;
    }

    public function limit($type, Collection $request)
    {
        return $this->from($this->table . ' as m')
            ->join(self::getContentTable($type) . ' as mc', 'm.id' , '=', 'mc.material_id')
            ->where('m.deleted_at', '=', null)
            ->select('m.*', 'mc.*', 'm.id')
            ->orderBy('m.id', 'desc')
            ->paginate($request->get('limit'));
    }

    public function getTypeById($id)
    {
        return $this->where('id', $id)->value('type');
    }

    public function delete()
    {
        $id   = $this->id;
        $type = $this->type;

        $row = parent::delete();

        self::model($type)->where('material_id', $id)->delete();

        return $row;
    }

}

