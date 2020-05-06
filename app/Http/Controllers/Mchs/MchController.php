<?php namespace App\Http\Controllers\Mchs;

use App\Http\Codes\Code;
use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Mchs\Mch;
use Libs\Arr;
use Libs\Response;
use App\Repositorys\Admin\MchStoreRepository;

class MchController extends BaseController {

    private $repository;

    public function rule()
    {
	    return  new Mch();
    }

    public function __construct(MchStoreRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    public function show()
    {
    	if(!$this->repository->check($this->user()->getMchId())){
    		return Response::error(Code::not_exists,"商户不存在");
	    }
        if(!$data=$this->repository->show($this->user()->getMchId())){
            return Response::error(WeiCode::get_mch_fail, '网络错误');
        }
        return Response::success('',$data);
    }

    public function update(ApiVerifyRequest $request){
	    $data = $request->all();
	    $data['banner'] = $request->input('banner',[]);
    	if(!$data = $this->repository->update($data,$this->user()->getMchId())){
		    return Response::error('', '网络错误');
	    }
	    $detial = "更新了商户ID:".$this->user()->getMchId()."的详情";
	    self::note("更新商户详情",$detial);
	    return Response::success('更新成功',$data);
    }

}