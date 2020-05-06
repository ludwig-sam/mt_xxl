<?php

namespace Libs\Payments\Special\Support;


class Config extends \Libs\Payments\Contracts\Config
{

    public function getKey()
    {
        return config('payment.notify_key');
    }

    public function checkMode()
    {
    }

    public function getMode()
    {
    }

    public function getBaseUri()
    {
    }
}
