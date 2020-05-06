<?php

namespace App\Providers;

use Abstracts\SmsInterface;
use Abstracts\UploaderInterface;
use App\Service\Wechat\Contracts\MediaInterface;
use App\Service\Wechat\Media;
use Providers\CmsChuanglan\Sms;
use Illuminate\Support\ServiceProvider;
use Providers\UploadApi\QiniuApi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            SmsInterface::class,
            function (){
                $config = config('sms.chuang523');

                return new Sms($config['account'], $config['password'], $config['host']);
            }
        );

        $this->app->bind(
            UploaderInterface::class,
            function (){
                return new QiniuApi([
                    'access_key' => config('qiniu.access_key'),
                    'secret_key' => config('qiniu.secret_key'),
                    'bucket'     => config('qiniu.bucket')
                ]);
            }
        );


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
