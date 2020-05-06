<?php

namespace Tests\Fans;


use App\Jobs\ProcessFansCreate;
use App\Service\Fans\Updating;
use App\Service\Listener\FansUpdateListener;
use Tests\TestCase;

class UpAllTest extends TestCase
{

    public function test_updating()
    {

        $listener = new FansUpdateListener();

        $fans_service = new Updating();

        $fans_service->pull();

        dispatch(new ProcessFansCreate($listener));

        $this->assertTrue(true);
    }

}