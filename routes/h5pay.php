<?php

use Libs\Route;

Route::match('create', [Route::post], 'pay.h5Order@create')->name('create_pay_order');

Route::match('card_list', [Route::get], 'pay.h5Order@cardList');

Route::match('card_group_list', [Route::get], 'pay.h5Order@cardGroupCards');

Route::match('calculation', [Route::post], 'pay.h5Order@calculation');

Route::match('store', [Route::get], 'pay.h5Order@store');

Route::match('member', [Route::get], 'pay.h5Order@memberInfo');

Route::match('card_consume', [Route::post], 'pay.h5Order@consume');

Route::match('payment/sure_pay', 'post', 'pay.h5Order@surePay');