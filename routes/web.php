<?php
use Libs\Route;


Route::match('/', [Route::get],  'web.home@home');

Route::match('card/activate', [Route::get], 'minipro.wechatCard@cardActivate')->name('cardActive');

Route::group('web', function (){

    Route::match('h5pay/scan_code', [Route::get], 'web.h5Pay@scanCode')->name('h5pay_scan_code');

    Route::match('h5pay/pay', [Route::get], 'web.h5Pay@pay')->name('h5_pay');

    Route::any('auth_callback', 'receive.wechat@auth')->name('wx_auth_callback');

    Route::any('wx_user_info', 'web.test@wxUserInfo');

    Route::any('session_flush', 'web.test@sessionFlush');


    Route::any('callback', 'web.auth@callback')->name('test_auth_callback');

    Route::any('auth', 'web.auth@auth');


}, ['want_json']);


