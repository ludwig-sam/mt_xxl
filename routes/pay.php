<?php

use Libs\Route;

/*---- xingbo s----*/

//pay
Route::match('create', [Route::get, Route::post], 'pay.order@create')->name('create_pay_order');
Route::match('refund', [Route::post], 'pay.order@refund')->name('refund_pay_order');
Route::match('refund_list', [Route::get, Route::post], 'pay.order@refundList')->name('pay_refund_list');
Route::match('pay_list', [Route::get, Route::post], 'pay.order@payList')->name('pay_pay_list');
Route::match('calculation', [Route::get, Route::post], 'pay.calculation@index')->name('pay_calculation');

//card
Route::match('wechat_card/get_by_code', [Route::get, Route::post], 'pay.card@getWxCardInfo');
Route::match('wechat_card/get_member_card', [Route::get, Route::post], 'pay.card@getMemberCard');
Route::match('wechat_card/consume', [Route::get, Route::post], 'pay.card@consume');
Route::match('wechat_card/consume_list', [Route::get, Route::post], 'pay.card@consumeList');

Route::match('wechat_card/scan_code', [Route::post], 'pay.card@scanCode');

/*---- xingbo e----*/


/*---- tww s----*/
Route::match('orderQuery', [Route::get], 'pay.order@orderQuery')->name('show_pay_order');
Route::match('refundQuery', [Route::get], 'pay.order@refundQuery')->name('show_refund_order');

Route::match('findExe', [Route::get], 'pay.exe@findExe');
/*---- tww e----*/

/*---- ygl s----*/
Route::match('channels_list', [Route::get], 'pay.channel@channelsList');
/*---- ygl e----*/
