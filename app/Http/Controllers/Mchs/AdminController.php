<?php namespace App\Http\Controllers\Mchs;

use App\Http\Rules;
use Libs\Response;
use App\Http\Codes\LeiCode;
use App\Repositorys\Admin\AdminRepository;
use App\Http\Requests\ApiVerifyRequest;
use App\Service\Nav\Nav;

class AdminController extends BaseController {

    private $repository;

    public function rule()
    {
        return  new Rules\Mchs\Admin();
    }

    public function __construct(AdminRepository $repository)
    {
        parent::notNeedPermission();

        parent::__construct();
        $this->repository = $repository;
    }

    public function getMe()
    {

        if (!$data=$this->repository->findMe($this->user()->getId())){
            return Response::error(LeiCode::not_exists,'获取我的信息失败');
        }

        $nav_service = new Nav();

        $data['navs'] = $nav_service->getNavs($this->user());

        return Response::success('',$data);
    }

    public function update(ApiVerifyRequest $request)
    {
        $data=$this->repository->update($this->user()->getId(), $request->all());

        if($data===true)
        {
	        $detial = "更新了管理员".$this->user()->getId();
	        self::note('更新管理员',$detial);
            return Response::success('修改成功', $data);
        }else
        {
            return Response::error($data[0],$data[1]);
        }
    }

}