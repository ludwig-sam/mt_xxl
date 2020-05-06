<?php namespace App\Http\Controllers\Mchs;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Arr;
use Libs\Response;
use App\Http\Codes\WeiCode;
use App\Repositorys\Admin\ExeOpratorRepository;

class ExeOpratorController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Mchs\ExeOprator();
    }

    public function __construct(ExeOpratorRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function create(ApiVerifyRequest $request){

    	if($this->repository->check("{$request['username']}")){
		    return Response::error(WeiCode::exists, '该用户名已存在');
	    }
	    $data = Arr::getIfExists($request->all(),['username','password','mobile','id_card','store_id','status','headurl']);
	    $data['mch_id'] = $this->user()->getMchId();
        if(!$id = $this->repository->save($data)){
            return Response::error(WeiCode::create_exeOprator_fail, '添加失败');
        }
        return Response::success('添加成功',compact('id'));
    }

    public function lists(ApiVerifyRequest $request){
        return Response::success('',$this->repository->limit($this->limitNum(),$request));
    }

    public function update(ApiVerifyRequest $request){
    	if(!$this->repository->find(intval($request['id']))){
		    return Response::error(WeiCode::not_exists,'该收银员不存在');
	    }

	    if( $exe_oprator =  $this->repository->check("{$request['username']}")){
    		if($exe_oprator->id != $request->get('id')){
			    return Response::error(WeiCode::exists, '该用户名已存在');
		    }

	    }
    	$data = Arr::getIfExists($request->all(),['username','password','mobile','id_card','store_id','status','headurl']);
	    $where = [
		    ['id','=',$request->all(['id'])],
		    ['mch_id','=',$this->user()->getMchId()]
	    ];
        if (!$this->repository->updateOne($data,$where)){
            return Response::error(WeiCode::update_exeOprator_fail,'网络错误');
        }
	    $detial = "更新了收银员ID:".$request['id']."的内容";
	    self::note("更新收银员",$detial);
        return Response::success('修改成功');
    }

    public function delete($id){
	    if(!$this->repository->find(intval($id))){
		    return Response::error(WeiCode::not_exists,'该收银员不存在');
	    }
	    $where = [
		    ['id','=',$id],
		    ['mch_id','=',$this->user()->getMchId()]
	    ];
        if (!$this->repository->remove($where)){
            return Response::error(WeiCode::delete_exeOprator_fail,'网络错误');
        }
	    $detial = "删除了收银员ID:".$id;
	    self::note("删除收银员",$detial);
        return Response::success('删除成功');
    }

	public function show($id){
		$where = [
			['mch_id',$this->user()->getMchId()]
		];
		if( !$data =  $this->repository->findOne($id,$where)){
			return Response::error(WeiCode::get_exeOprator_fail, '该收银员不存在');
		}
		return Response::success('',$data);
	}

    public function updateStatus(ApiVerifyRequest $request){

        $oprator = $this->repository->find(intval($request->get("id")));

        if(!$oprator){
            return Response::error(Code::not_exists, '操作员不存在');
        }

        $oprator->status = $request->get('status');

        if(!$oprator->save()){
            return Response::error(Code::upload_fail, "更新失败");
        }
	    $detial = "更新了收银员ID:".$request['id']."的状态为:".$request->get('status');
	    self::note("更新收银员状态",$detial);
        return Response::success("更新成功");
    }
}