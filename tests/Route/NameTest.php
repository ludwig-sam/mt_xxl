<?php namespace Tests\Reply;

use Libs\Route;
use Tests\TestCase;

class NameTest extends TestCase{

    public function test_namedParam(){
        $this->assertEquals('pay_notify/2', substr(Route::named('pay_notify', 2), -12));
    }


}
