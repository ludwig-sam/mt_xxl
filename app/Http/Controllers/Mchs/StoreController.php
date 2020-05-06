<?php namespace App\Http\Controllers\Mchs;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Arr;
use Libs\Response;
use App\Http\Codes\WeiCode;
use App\Repositorys\Admin\StoreRepository;

class StoreController extends BaseController{

	private $repository;

	public function rule(){
		return new Rules\Mchs\Store();
	}

	public function __construct(StoreRepository $repository){
		parent::__construct();
		$this->repository = $repository;
	}

	public function create(ApiVerifyRequest $request){
		$data = Arr::getIfExists($request->all(),['pic','name','province','city','county','address','phone','status']);
		$data['mch_id'] = $this->user()->getMchId();
		if( !$id = $this->repository->save($data)){
			return Response::error(WeiCode::create_store_fail, '网络错误');
		}
		return Response::success('添加成功', compact('id'));
	}

	public function lists(ApiVerifyRequest $request){
		return Response::success('', $this->repository->limit($this->limitNum(), $request,$this->user()->getMchId()));
	}

	public function update(ApiVerifyRequest $request){
		if(!$this->repository->find($request->all(['id']))){
			return Response::error(WeiCode::not_exists,'该门店不存在');
		}
		$data = Arr::getIfExists($request->all(),['pic','name','province','city','county','address','phone','status']);
		$where = [
			['id','=',$request->all(['id'])],
			['mch_id','=',$this->user()->getMchId()]
		];
		if(!$this->repository->updateOne($data,$where)){
			return Response::error(WeiCode::update_store_fail, '网络错误');
		}
		$detial = "更新了门店ID:".$request['id']."的内容";
		self::note("更新门店",$detial);
		return Response::success('更新成功');
	}

	public function delete($id){
		$where = [
			['id',$id],
			['mch_id',$this->user()->getMchId()]
		];
		if( !$this->repository->remove($where)){
			return Response::error(WeiCode::delete_store_fail, '该门店不存在，无法删除');
		}
		$detial = "删除了门店ID:".$id;
		self::note("删除门店",$detial);
		return Response::success('删除成功');
	}

	public function show($id){

		$where = [
			['mch_id',$this->user()->getMchId()]
		];
		if( !$data =  $this->repository->findOne($id,$where)){
			return Response::error(WeiCode::get_store_fail, '该门店不存在');
		}
		return Response::success('',$data);
	}
}