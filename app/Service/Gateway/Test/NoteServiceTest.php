<?php

namespace App\Service\Gateway\Test;


use App\Service\Gateway\NoteService;
use App\Service\Gateway\TestHelper;
use Tests\TestCase;

class NoteServiceTest extends TestCase
{

    public function test_write()
    {

        $route = [
            'testa',
            'testc',
            'testm'
        ];

        $ip = new TestHelper\IpService();

        $ip->setRoute($route);

        $note_service = new NoteService($ip);

        $count        = $note_service->count();

        $note_service->write();

        $this->assertEquals($count + 1, $note_service->count());
    }
}