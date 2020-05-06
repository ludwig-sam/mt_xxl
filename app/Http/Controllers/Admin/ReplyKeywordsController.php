<?php namespace App\Http\Controllers\Admin;



use Libs\Response;
use App\Models\ReplyKeywords;
use Illuminate\Http\Request;

class ReplyKeywordsController extends BaseController {


    public function rule()
    {

    }

    public function keywordList(ReplyKeywords $reply_keywords)
    {
		return Response::success('',$reply_keywords->keywordsList($this->limitNum()));
    }



}