<?php

namespace Tests\Fans;


use App\Jobs\ProcessFansUpdate;
use App\Service\Fans\Cache;
use App\Service\Listener\FansUpdateListener;
use Tests\TestCase;

class CacheTest extends TestCase
{

    private function cache()
    {
        $cache = new Cache('test_mt_fans_openid');

        return $cache;
    }

    public function test_fansCache()
    {
        $cache = $this->cache();

        $cache->flush();

        $arr = [
            1,
            2,
            3,
            4,
            5
        ];

        foreach ($arr as $value){
            $cache->push($value);
        }

        $this->assertEquals(count($arr), $cache->len());
    }

    public function test_flush()
    {
        $cache = $this->cache();

        $cache->flush();

        $this->assertEquals(0, $cache->len());
    }

    public function test_limit()
    {
        $cache = $this->cache();

        $cache->flush();

        $arr = [
            1,
            2,
            3,
            4,
            5
        ];

        foreach ($arr as $value){
            $cache->push($value);
        }

        $this->assertEquals([], $cache->limit(0));

        $this->assertEquals([1], $cache->limit(1));

        $this->assertEquals([2,3,4,5], $cache->limit(4));

        $this->assertEquals([], $cache->limit(2));
    }


}