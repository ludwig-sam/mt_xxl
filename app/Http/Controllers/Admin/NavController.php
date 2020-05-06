<?php namespace App\Http\Controllers\Admin;



use Libs\Response;
use App\Service\Nav\Nav;
use Illuminate\Http\Request;

class NavController extends BaseController {


    public function rule()
    {
    }

    public function chNav(Request $request)
    {
       $navService = new Nav();
       $data = $navService->chNav($this->user(), intval($request->get('mch_id')));

       return Response::success('', $data);
    }
}