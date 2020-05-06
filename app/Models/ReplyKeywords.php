<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReplyKeywords extends Model {

    protected $table = 'wechat_reply_keywords';

    protected $fillable = [
        'keyword','reply_id'
    ];

    public $timestamps = false;

    public function keywordsList($limit){
    	$keywords = $this->select('*')->orderBy('id','desc')->paginate($limit);

		foreach($keywords as $keyword){
			$where=[
				['id',$keyword['reply_id']]
			];
			if($replyModel = ReplyModel::where($where)->first()){
				$keyword->created_at = $replyModel->created_at->format('Y-m-d H:i:s');

				if($replyMaterials = $replyModel->hasMaterial){
					 $keyword->comment =  $this->materialCount($replyMaterials);
				}
			}
		}
		return $keywords;
    }

    public function materialCount($replyMaterials){
	    $count=0;
    	foreach($replyMaterials as $reply_material){
    		if($material = MaterialModel::find($reply_material->material_id)){
				$count+=1;
		    }
	    }
	    return $count;
    }

	public function hasReplyMaterial()
	{
		return $this->hasMany(ReplyMaterialModel::class, 'reply_id', 'reply_id');
	}
}

