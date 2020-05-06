<?php namespace App\Repositorys\Admin;


use App\Models\MchModel;
use App\Models\MchTagsModel;
use App\Models\MchMemberLevelModel;
use App\Models\TagsModel;
use App\Service\Export\Contracts\ExportSupportInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MchRepository implements ExportSupportInterface{

    private $model;

    public function __construct(MchModel $model)
    {
        $this->model = $model;
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function stop($id, $is_stop)
    {
        return $this->model->where('id', $id)->update([
            'is_stop' => $is_stop
        ]);
    }

    public function update($data)
    {
        $tags=[];
        $member_level=[];

        if(isset($data['member_level'])) {
            $member_level = $data['member_level'];
            unset($data['member_level']);
        }

        MchTagsModel::where('mch_id', $data['id'])->delete();
        if(isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $this->model->find($data['id'])
            ->fill($data)
            ->save();

        if($member_level) {
            foreach ($member_level as $level) {
                MchMemberLevelModel::where('mch_id', $data['id'])
                    ->where('member_level_id', $level['id'])
                    ->update(['discount' => $level['discount'],
                        'consume' => $level['consume'],
                        'point' => $level['point']]);
            }
        }

        if($tags) {
            $tags=array_unique($tags);

            foreach ($tags as $tag) {
                $temp = new MchTagsModel();
                $temp->mch_id = $data['id'];
                $temp->tag_id = $tag;
                $temp->save();
            }
        }
        return true;
    }

    public function getLevel($id)
    {
        $data= MchMemberLevelModel::select('member_level_id as id','discount','consume','point')->where('mch_id',$id)->get();
        return $data;
    }

    public function show($id)
    {
        $member_level = MchMemberLevelModel::select('member_level_id as id','discount','consume','point')->where('mch_id',$id)->get();

        $data=$this->model
            ->leftJoin('mch_category','mch.mch_category_id','=','mch_category.id')
            ->select('mch.id','mch.sort','mch.name','mch.manager','mch.manager_phone','mch.address','mch.is_stop','mch.is_hot','mch.logo','mch.description','mch_category.name as mch_category_name','mch_category.id as mch_category_id','mch.payment_way','mch.created_at','mch.updated_at')
            ->where('mch.id',$id)->first();
        $tag = MchTagsModel::where('mch_id',$id)->get();
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

    public function delete($id)
    {
        MchMemberLevelModel::where('mch_id',$id)->delete();
        MchTagsModel::where('mch_id',$id)->delete();
        return $this->model->find($id)->delete();
    }

    public function page($limit,$request)
    {
        $temp= (new MchModel())
            ->leftJoin('mch_category','mch.mch_category_id','=','mch_category.id')
            ->select('mch.id','mch.sort','mch.name','mch.manager','mch.manager_phone','mch.address','mch.is_hot','mch.is_stop','mch.banner','mch.logo','mch.description','mch_category.name as mch_category_name','mch.payment_way','mch.created_at','mch.updated_at','mch.deleted_at')
            ->when($request->get('mch_category_name'), function ($query) use($request) {
                return $query->where('mch_category.name','like','%'. $request->get('mch_category_name').'%');
            })
            ->when($request->get('cid'), function ($query) use($request){
                return $query->where('mch.mch_category_id', $request->get('cid'));
            })
            ->when($request->get('name'), function ($query) use($request){
                return $query->where('mch.name','like','%'. $request->get('name').'%');
            })
            ->orderBy('id','desc')
            ->paginate($limit);

        foreach($temp as $t)
        {
            $tag_mch= DB::table('mch_tags')->where('mch_tags.mch_id','=',$t->id)->get();
            $tag_name=[];
            foreach ($tag_mch as $tag)
            {
                $name= DB::table('tags')->select('name')->where('id','=',$tag->tag_id)->get();
                foreach ($name[0] as $n)
                {
                    array_push($tag_name,$n);
                }
            }
            $t->tags_name=$tag_name;
        }

        return $temp;
    }

    public function miniProPage($limit,$request)
    {
        $temp = (new MchModel())
            ->leftJoin('mch_category','mch.mch_category_id','=','mch_category.id')
            ->select('mch.id','mch.sort','mch.name','mch.manager','mch.manager_phone','mch.address','mch.is_hot','mch.is_stop','mch.banner','mch.logo','mch.description','mch_category.name as mch_category_name','mch.payment_way','mch.created_at','mch.updated_at')
            ->when($request->get('mch_category_name'), function ($query) use($request) {
                return $query->where('mch_category.name','like','%'. $request->get('mch_category_name'));
            })
            ->when($request->get('cid'), function ($query) use($request){
                return $query->where('mch.mch_category_id', $request->get('cid'));
            })
            ->when($request->get('name'), function ($query) use($request){
                return $query->where('mch.name','like','%'. $request->get('name'));
            })
            ->where('mch.deleted_at',null)
            ->where('mch.is_stop',0)
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->paginate($limit);

        foreach($temp as $t)
        {
            $tag_mch= DB::table('mch_tags')->where('mch_tags.mch_id','=',$t->id)->get();
            $tag_name=[];
            foreach ($tag_mch as $tag)
            {
                $name= DB::table('tags')->select('name')->where('id','=',$tag->tag_id)->get();
                foreach ($name[0] as $n)
                {
                    array_push($tag_name,$n);
                }
            }
            $t->tags_name=$tag_name;
        }

        return $temp;
    }

    public function save($data)
    {
        $tags=[];
        $member_level=[];

        if(isset($data['member_level'])) {
            $member_level = $data['member_level'];
            unset($data['member_level']);
        }

        if(isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $this->model->fill($data);
        $this->model->save();

        if($member_level) {
            foreach ($member_level as $level) {
                $temp = new MchMemberLevelModel();
                $temp->mch_id = $this->model->id;
                $temp->member_level_id = $level['id'];
                $temp->discount = $level['discount'];
                $temp->consume = $level['consume'];
                $temp->point = $level['point'];
                $temp->save();
            }
        }

        if($tags) {

           $tags = array_unique($tags);

            foreach ($tags as $tag) {
                $temp = new MchTagsModel();
                $temp->mch_id = $this->model->id;
                $temp->tag_id = $tag;
                $temp->save();
            }
        }
        return $this->model->id;
    }

	public function miniproShow($id){
    	 $data = DB::table('mch')
		    ->leftJoin('mch_category','mch.mch_category_id','mch_category.id')
		    ->select('mch.*','mch_category.name as mch_category_name')
		    ->where('mch.id',$id)
		    ->first();
    	$data->banner = json_decode($data->banner);
		return $data;
	}

	public function hotMchList($limit){
    	return $this->model->where('is_hot',1)->where('is_stop',0)->orderBy('sort')->orderBy('id','desc')->paginate($limit);
	}

	function exportByIds($ids, $request){

		$fields = ['mch.*','m_c.name as mch_category_name'];

		return $this->model->exportFromLimit($ids, $fields);
	}

	function filterNoLimit($request){
		$request = new Collection($request);

		return $this->model->listNoLimit($request);
	}

	public function cells($list){
		$result      = [];
		$header       = [
			"ID","商户名称","商户负责人","负责人电话","商户分类"
		];

		foreach ($list as $row){
			$result[] = [
				$row['id'],
				$row['name'],
				$row['manager'],
				$row['manager_phone'] . ' ',
				$row['mch_category_name'],
			];
		}

		return [$header, $result];
	}

	public function check($mch_id){
		return $this->model->find($mch_id);
	}
}