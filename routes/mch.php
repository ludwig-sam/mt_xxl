<?php

use Libs\Route;

/*---- tangweiwei s----*/
//门店
Route::match('store/create', [Route::post], 'mchs.store@create')->name('create_store');
Route::match('store/lists', [Route::get], 'mchs.store@lists');
Route::match('store/show/{id}', [Route::get, Route::post], 'mchs.store@show')->where('id', '\d+');
Route::match('store/update', [Route::post], 'mchs.store@update');
Route::match('store/delete/{id}', [Route::get], 'mchs.store@delete')->where('id', '\d+');

//收银机
Route::match('exe/create', [Route::post], 'mchs.exe@create');
Route::match('exe/show/{id}', [Route::get], 'mchs.exe@show')->where('id', '\d+');
Route::match('exe/update', [Route::post], 'mchs.exe@update');
Route::match('exe/lists', [Route::get], 'mchs.exe@lists');
Route::match('exe/delete/{id}', [Route::get], 'mchs.exe@delete')->where('id', '\d+');

//收银员
Route::match('exe_oprator/create', [Route::post], 'mchs.exeOprator@create');
Route::match('exe_oprator/lists', [Route::get], 'mchs.exeOprator@lists');
Route::match('exe_oprator/show/{id}', [Route::get, Route::post], 'mchs.exeOprator@show')->where('id', '\d+');
Route::match('exe_oprator/update', [Route::post], 'mchs.exeOprator@update');
Route::match('exe_oprator/delete/{id}', [Route::get], 'mchs.exeOprator@delete')->where('id', '\d+');

//账单
Route::match('exe/bills', [Route::get], 'mchs.payOrder@lists')->name('mch_bills');
Route::match('exe/refund_lists', [Route::get], 'mchs.payOrder@refundLists');
Route::match('pay_method', [Route::get], 'mchs.payMethod@lists');

//商户概况
Route::match('show', [Route::get], 'mchs.mch@show');
Route::match('update', [Route::post], 'mchs.mch@update');

//优惠券
Route::match('card_lists', [Route::get], 'mchs.card@cardList');

Route::match('pay_order/export_select', [Route::get, Route::post], 'mchs.payOrder@exportSelect');
Route::match('pay_order/export_filter', [Route::get, Route::post], 'mchs.payOrder@exportFilter');

/*---- tangweiwei e----*/


/*---- ygl s----*/
//账户
Route::match('me', [Route::get], 'mchs.admin@getMe');
Route::match('me_update', [Route::post], 'mchs.admin@update');
/*---- ygl e----*/

/*---- li s----*/

//exe
Route::match('exe/update_status', [Route::post, Route::get], 'mchs.exeOprator@updateStatus');
Route::match('card/exe_coupon_list', [Route::post, Route::get], 'mchs.card@exeNormalCouponList');

//exe 支付码
Route::match('exe/pay_qrcode/{id}', [Route::get], 'mchs.exeQrcode@payQrcode');

//支付配置
Route::match('pay_config/get', [Route::get], 'mchs.payConfig@get');
Route::match('pay_config/update', [Route::post], 'mchs.payConfig@update');

//提现

Route::match('bank', 'post', 'mchs.BankCard@create');
Route::match('bank', 'get', 'mchs.BankCard@limit');
Route::match('bank/{id}', 'put', 'mchs.BankCard@update')->where('id', '\d+');
Route::match('bank/{id}', 'get', 'mchs.BankCard@get')->where('id', '\d+');
Route::match('bank/{id}', 'delete', 'mchs.BankCard@delete')->where('id', '\d+');

Route::match('withdraw', 'post', 'mchs.withdraw@create');
Route::match('withdraw', 'get', 'mchs.withdraw@limit');
Route::match('withdraw/{id}', 'put', 'mchs.withdraw@update')->where('id', '\d+');
Route::match('withdraw/{id}', 'get', 'mchs.withdraw@get')->where('id', '\d+');
Route::match('withdraw/{id}', 'delete', 'mchs.withdraw@delete')->where('id', '\d+');

Route::match('withdraw/bill/count', 'get', 'mchs.withdraw@count');

Route::match('withdraw/export', 'get', 'mchs.withdraw@export');
Route::match('withdraw/export_select', 'get', 'mchs.withdraw@exportSelect');


