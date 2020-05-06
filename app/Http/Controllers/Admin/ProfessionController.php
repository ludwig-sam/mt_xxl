<?php namespace App\Http\Controllers\Admin;

use Libs\Response;
use App\Models\ProfessionModel;

class ProfessionController extends BaseController{

	private $model;

	public function rule(){

	}

	public function __construct(ProfessionModel $model){
		parent::__construct();
		$this->model = $model;
	}

	public function lists(){
		return Response::success('获取成功', $this->model->all());
	}
}