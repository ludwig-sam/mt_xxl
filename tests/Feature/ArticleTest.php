<?php

namespace Tests\Reply;

use Libs\Route;
use Tests\Api\ApiBase;

class ArticleTest extends ApiBase
{


    public function testValidateFail()
    {
        $this->post(Route::named('create_article'), [
            'title' => ''
        ]);

        $this->false($this->isSuccess());
    }

    public function testValidateSuccess()
    {
        $this->post(Route::named('create_article'), [
            'title' => "自动验证成功" ,
            'introduce' => "自动验证成功案例",
            'content' => 'today is sunny day'
        ]);

        $this->true($this->isSuccess());
    }

    public function testCreate()
    {
        $this->post(Route::named('create_article'), [
            'title' => "how to start php web server" . mt_rand(0 ,1111111),
            'introduce' => "this is introduce, who care",
            'content' => 'today is sunny day'
        ]);

        if(!$this->isSuccess()){
            $this->error($this->getContent(true));
        }

        $create_id = $this->getContent()->data->id->id;

        $this->get( '/api/admin/article/' . $create_id);

        $select_id = $this->getContent()->data->id;

        $this->assertEquals($create_id, $select_id);
    }
}


