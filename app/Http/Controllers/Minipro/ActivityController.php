<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 2018/8/23
 * Time: 14:03
 */

namespace App\Http\Controllers\Minipro;



use App\Http\Codes\Code;
use Libs\Response;
use App\Models\ActivityModel;


class ActivityController extends  BaseController{

	function rule(){

	}

	public function activityList(ActivityModel $model){
		return Response::success('',$model->activatyLimit($this->limitNum()));
	}

	public function show($id,ActivityModel $model){
		if(!$data = $model->find(intval($id))){
			return Response::error(Code::not_exists,"该活动不存在");
		}
		return Response::success('',$data);
	}
}