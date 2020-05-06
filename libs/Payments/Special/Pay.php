<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/19
 * Time: 下午3:40
 */

namespace Libs\Payments\Special;

use Illuminate\Support\Collection;
use Libs\Payments\Contracts\Config;

class Pay
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function refund(Collection $order)
    {
        // TODO: Implement refund() method.
    }

    public function verify(Collection $params)
    {
    }
}