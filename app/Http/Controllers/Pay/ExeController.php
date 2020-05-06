<?php namespace App\Http\Controllers\Pay;



use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules\Pay\PayRule;
use Libs\Response;
use App\Models\ExeModel;

class ExeController extends BaseController {


    public function rule()
    {
        return new PayRule();
    }

    public function findExe(ApiVerifyRequest $request){
		$exeModel = new ExeModel();
	    $where = [
		    ['mch_id' , '=', $this->user()->getMchId()],
		    ['dev_no','=',$request['dev_no']],
	    ];
		if(!$data = $exeModel->findExe($where)){
			return Response::error(WeiCode::get_exe_fail,'收银机不存在');
		}
		return  Response::success('', $data);
    }
}