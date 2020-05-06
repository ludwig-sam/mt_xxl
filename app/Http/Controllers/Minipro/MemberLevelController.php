<?php namespace App\Http\Controllers\Minipro;


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

	public function list()
    {
        $list = $this->model()->get();

        return Response::success('', [
            'data' => $list
        ]);
    }

}