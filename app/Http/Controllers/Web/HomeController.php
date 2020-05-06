<?php namespace App\Http\Controllers\Web;



use App\Http\Codes\Code;
use App\Http\Controllers\Controller;
use Libs\Response;

class HomeController extends Controller {

    public function module()
    {
        return 'web';
    }

    public function rule()
    {
    }

    public function home()
    {
        return Response::error(404, 'page not found');
        return view('home.home');
    }
}