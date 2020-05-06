<?php namespace App\Http\Controllers\Admin;


use Libs\Arr;
use Libs\Response;
use App\Models\MemberlevelModel;
use App\Service\MemberLevel\MemberLevel;
use Illuminate\Http\Request;

class MemberLevelController extends BaseController{



    public function rule()
    {
    }

    private function model()
    {
        return new MemberlevelModel();
    }

    private function memberLevelService()
    {
        static $service;

        if(!$service){
            $service = (new MemberLevel());
        }

        return $service;
    }

    private function getRow($id)
    {
        return $this->memberLevelService()->getRow($id);
    }

    private function updateCheck($level_row, $exp, $consume)
    {
        $this->memberLevelService()->updateCheck($level_row, $exp, $consume);
    }

	public function list()
    {
        $list = $this->model()->get();

        return Response::success('', [
            'data' => $list
        ]);
    }

    public function update($id, Request $request)
    {
        $level_row  = $this->getRow($id);

        $exp        = (int)abs($request->get('exp'));
        $consume    = (float)abs($request->get('consume'));

        $this->updateCheck($level_row, $exp, $consume);

        $update_data = [
            'exp'     => $exp,
            'consume' => $consume,
            'icon'    => $request->get('icon')
        ];

        $level_row->update($update_data);

	    self::note("更新用户等级:", "更新了用户等级ID:".$request['id']."升级所需经验为：".$exp.",所需消费金额为：".$consume);

        return Response::success();
    }

    public function get($id)
    {
        $level_row = $this->getRow($id);

        return Response::success('', $level_row);
    }

}