<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway\Test;

use App\Service\Gateway\GatewayConfig;
use App\Service\Gateway\GatewayConfigCache;
use Tests\TestCase;


class GatewayConfigCacheTest extends TestCase
{

    public function test_set()
    {
        $gateway_cache = new GatewayConfigCache();

        $gateway_config = new GatewayConfig();

        $gateway_config->set('ip_black_list', ['127.0.0.1']);

        $this->assertEquals([
            '127.0.0.1'
        ], $gateway_cache->get('ip_black_list'));
    }

    public function test_get()
    {
        $gateway_cache = new GatewayConfigCache();

        $this->assertEquals([
            '127.0.0.1'
        ], $gateway_cache->get('null', [
            '127.0.0.1'
        ]));
    }

}