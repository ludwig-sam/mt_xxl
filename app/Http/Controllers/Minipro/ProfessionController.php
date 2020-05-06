<?php namespace App\Http\Controllers\Minipro;

use App\Http\Codes\Code;
use Libs\Filter;
use Libs\Response;
use App\Models\ProfessionModel;
use Illuminate\Http\Request;

class ProfessionController extends BaseController{

	private $model;

	public function rule(){

	}

	public function __construct(ProfessionModel $model){
		parent::__construct();
		$this->model = $model;
	}

	public function lists(){
		return Response::success('获取成功', $this->model->orderBy('id','desc')->paginate($this->limitNum()));
	}
}