<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiVerifyRequest;
use App\Http\Rules;
use Libs\Arr;
use Libs\Response;
use App\Http\Codes\WeiCode;
use App\Repositorys\Admin\AdvertRepository;

class AdvertController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Admin\Advert();
    }

    public function __construct(AdvertRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    public function create(ApiVerifyRequest $request)
    {
	    $data = Arr::getIfExists($request->all(),[ 'pic', 'link','sort','advert_position_id','desc']);
        if(!$this->repository->create($data)){
            return Response::error(WeiCode::create_advert_fail, '网络错误');
        }
        return Response::success('添加成功');
    }

	public function advertList(ApiVerifyRequest $request)
	{
		$advert_position_id = $request->get('advert_position_id');

		return Response::success('获取成功',$this->repository->advertList($advert_position_id,$this->limitNum()));
	}

	public function getAdvert($id)
	{
		if(!$data = $this->repository->getAdvert($id)){
			return Response::error(WeiCode::get_advert_fail, '该广告不存在');
		}
		return Response::success('获取成功',$data);
	}

	public function update(ApiVerifyRequest $request)
	{
		$id = $request->get('id');
		if(!$this->repository->getAdvert($id)){
			return Response::error(WeiCode::update_advert_fail, '该广告不存在');
		}
		$data = Arr::getIfExists($request->all(),[ 'pic', 'link','desc','sort']);

		if(!$this->repository->update($data,$id)){
			return Response::error(WeiCode::update_advert_fail, '网络错误');
		}
		$detial = "更新了广告ID:".$id;
		self::note("更新广告",$detial);
		return Response::success('修改成功');
	}

	public function delete($id)
	{
		if(!$this->repository->getAdvert($id)){
			return Response::error(WeiCode::delete_advert_fail, '该广告不存在');
		}
		if(!$this->repository->delete($id)){
			return Response::error(WeiCode::delete_advert_fail, '网络错误');
		}
		$detial = "删除了广告ID:".$id;
		self::note("删除广告",$detial);
		return Response::success('删除成功');
	}
}