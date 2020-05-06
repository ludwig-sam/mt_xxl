<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Providers\AliyunException;

class ExceptionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([ realpath(__DIR__ . '/../../config/exception.php') => config_path(realpath(__DIR__ . '/../../config/exception.php')) ]);
    }

    public function register()
    {

        $this->app->singleton('exception', function ($app) {
            $config = $app['config']['exception'];
            return new AliyunException([
                'access_key_id'     => array_get($config, 'access_key_id'),
                'access_key_secret' => array_get($config, 'access_key_secret'),
                'endpoint'          => array_get($config, 'endpoint'),
                'project'           => array_get($config, 'project'),
                'store'             => array_get($config, 'store'),
                'topic'             => array_get($config, 'topic'),
            ]);
        });

    }
}
