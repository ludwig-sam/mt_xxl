<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午12:12
 */

namespace Tests\Dispatch;


use Illuminate\Support\Str;
use Tests\Dispatch\Providers\HookInterface;
use Tests\TestCase;

class DispatchTest extends TestCase
{

    public function test_dis()
    {
        $hooks = [
            'a', 'b' ,'c' , 'd' ,'e'
        ];

        foreach ($hooks as $hook){
            $this->make($hook)->hanlder(['test' => "realy test"]);
        }

        $this->assertTrue(true);

    }

    private function make($name) : HookInterface
    {
        $class = __NAMESPACE__ . '\\Providers\\' . Str::studly($name) . 'hook';
        return new $class;
    }
}