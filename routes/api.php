<?php

use Libs\Route;
use Libs\RouteLoader;

$config = config('route.api', []);

RouteLoader::load($config, __DIR__);

Route::any('wechat_serve', 'receive.wechat@serve');

Route::any('pay_notify/{order_no}', 'receive.PayNotify@index')->name('pay_notify')->middleware('log_request');
Route::any('recharge_notify/{id}', 'receive.PayNotify@recharge')->name('recharge_notify')->middleware('log_request');

Route::match('upload', [Route::post], 'pub.upload@upload')->middleware('cross_http');

Route::match('route/callback', [Route::post], 'web.route@callback');

Route::match('route/content', [Route::get], 'web.route@content');

Route::match('wx_media', [Route::get], 'web.media@get');

Route::match('order_status', [Route::get, Route::post], 'receive.payment@getStatus')->name('order_status')->middleware('want_json');

Route::match('cashier_login', [Route::get, Route::post], 'pay.cashier@login')->name('pay_cashier_login')->middleware('want_json');

