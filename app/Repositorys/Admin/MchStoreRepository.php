<?php namespace App\Repositorys\Admin;


use App\Models\MchModel;
use App\Models\MchTagsModel;
use App\Models\MchMemberLevelModel;
use App\Models\TagsModel;
use Illuminate\Support\Facades\DB;

class MchStoreRepository {

    private $model;

    public function __construct(MchModel $model)
    {
        $this->model = $model;
    }

    public function show($mch_id)
    {
        $member_level = MchMemberLevelModel::select('member_level_id as id','discount','consume','point')->where('mch_id',$mch_id)->get();
	    $data = $this->model
		    ->leftJoin('mch_category','mch.mch_category_id','=','mch_category.id')
		    ->select('mch.id','mch.name','mch.manager','mch.manager_phone','mch.is_hot','mch.logo','mch.description','mch_category.name as mch_category_name','mch.banner')
		    ->where('mch.id',$mch_id)->first();
        $tag = MchTagsModel::where('mch_id',$mch_id)->get();
        $tags = array();

        foreach ($tag as $temp)
        {
           $t= TagsModel::find($temp->tag_id);
           array_push($tags,$t);
        }

        $data->member_level=$member_level;
        $data->tags = $tags;

        return $data;
    }

	public function update(array $data, $id){
		MchTagsModel::where('mch_id',$id)->delete();
    	if(isset($data['tags'])){
		    $tags = $data['tags'];
		    unset($data['tags']);
		    foreach($tags as $tag){
		    	$mchTag = new MchTagsModel();
		    	$mchTag->tag_id = $tag;
		    	$mchTag->mch_id = $id;
		    	$mchTag->save();
		    }
	    }
	    $mch = $this->model->find($id);
		$mch->fill($data);

		return $mch->save();
	}

	public function check($mch_id){
    	return $this->model->find($mch_id);
	}
}