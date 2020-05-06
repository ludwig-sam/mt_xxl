<?php

namespace App\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EasyWechatService implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app) {
            return app()->get('sls.writer');
        };
    }

}
