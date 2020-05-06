<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/4
 * Time: 上午9:54
 */

namespace App\Service\Card\test;


use App\Service\Card\CodeService;
use Tests\TestCase;

class CodeScanTest extends TestCase
{

    public function test_scan()
    {
        $code_service = new CodeService();
        $code = '123456';

        $code_service->scan($code);

        $this->assertTrue($code_service->isScan($code));
    }

    public function test_expire()
    {
        $code_service = new CodeService(1);
        $code = '123';

        sleep(2);

        $code_service->scan($code_service->isScan($code));

        $this->assertFalse($code_service->isScan($code));
    }
}