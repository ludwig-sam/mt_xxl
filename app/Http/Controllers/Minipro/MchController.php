<?php namespace App\Http\Controllers\Minipro;

use App\Http\Codes\WeiCode;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Repositorys\Admin\CardRepository;
use App\Repositorys\Admin\MchCategoryRepository;
use App\Repositorys\Admin\MchRepository;
use Illuminate\Http\Request;

class MchController extends BaseController{

	public function rule(){

	}

	public function categoryList(MchCategoryRepository $mchCategoryRepository){
		return Response::success("", [
			"data" => $mchCategoryRepository->validList(),
		]);
	}

	public function catsMchLimit(Request $request, MchRepository $mchRepository){
		return Response::success('', $mchRepository->miniProPage($this->limitNum(), $request));
	}

	public function show(ApiVerifyRequest $request, MchRepository $mchRepository){
		if( !$data = $mchRepository->miniproShow($request->get('id'))){
			return Response::error(WeiCode::get_mch_fail, '该商户不存在');
		}
		return Response::success('', $data);
	}

	public function showMchCard(ApiVerifyRequest $request, CardRepository $cardRepository){
		return Response::success('', $cardRepository->miniproCardLimit($request->get('mch_id'), $this->limitNum()));
	}

	public function hotMchList(MchRepository $mchRepository){
		return Response::success('',$mchRepository->hotMchList($this->limitNum()));
	}
}