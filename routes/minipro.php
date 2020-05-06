<?php

use Libs\Route;


/*---- li s----*/

//分类
Route::match('mch/category_list', [Route::get], 'minipro.mch@categoryList');

//积分商城
Route::match('exchange_list', [Route::get, Route::post], 'minipro.exchangeShop@list');
Route::match('exchange', [Route::post], 'minipro.exchangeShop@exchange');

//会员
Route::match('member/activate', [Route::post], 'minipro.wechatCard@memberActivate')->name('memberActivate');
Route::match('member/point_list', [Route::get], 'minipro.member@pointList');
Route::match('member/pay_list', [Route::get], 'minipro.member@payList');
Route::match('member/my_info', [Route::get], 'minipro.member@myInfo');
Route::match('member/prepare_receive_m_card', [Route::get], 'minipro.member@prepareReceiveMCard');
Route::match('member/get_registe_reward', [Route::get], 'minipro.member@getRegisteReward');

//会员等级
Route::match('member/level_list', [Route::get], 'minipro.memberLevel@list');

//短信
Route::match('sms/send', [Route::post], 'minipro.sms@send');

//卡券
Route::match('card/get_wx_card/{wx_card_id}', [Route::get], 'minipro.card@getWxCard');
Route::match('card/prepare_receive_m_card/{id}', [Route::get], 'minipro.card@prepareReceiveCard');

//扫码状态
Route::match('member/card_status', [Route::get], 'minipro.CardCode@isScan');
Route::match('card_status/scan_complete', [Route::post], 'minipro.CardCode@scanComplete');

//用户配置
Route::match('member_config/pay_pwd', 'post', 'minipro.memberAccountConfig@updatePayPwd');
Route::match('member_config/has_pay_pwd', 'get', 'minipro.memberAccountConfig@isHasPayPwd');

//充值
Route::match('recharge', 'post', 'minipro.recharge@recharge');
Route::match('recharge/get', 'get', 'minipro.recharge@get')->where('id', '\d+');

//支付码
Route::match('paycode/balance_code', 'get', 'minipro.PaymentCode@balanceCode');

Route::match('paycode/balance_qrcode', 'get', 'minipro.PaymentCodeShow@qrcode')->name('payment_qrcode');
Route::match('paycode/balance_barcode', 'get', 'minipro.PaymentCodeShow@barcode')->name('payment_barcode');
Route::match('paycode/order', 'get', 'minipro.PaymentCode@order');
Route::match('payment/sure_pay', 'post', 'minipro.PaymentCode@surePayment');


/*---- li e----*/

/*---- tang s----*/

Route::match('member/profession', [Route::get], 'minipro.profession@lists');

Route::match('member/card', [Route::get, Route::post], 'minipro.cardCode@lists');

Route::match('mch', [Route::get], 'minipro.mch@show');
Route::match('mch/card', [Route::get], 'minipro.mch@showMchCard');
Route::match('mch/hot', [Route::get], 'minipro.mch@hotMchList');

Route::match('advert', [Route::get], 'minipro.advertPosition@lists');

Route::match('activity/list', [Route::get, Route::post], 'minipro.activity@activityList');
Route::match('activity/show/{id}', [Route::get, Route::post], 'minipro.activity@show');

/*---- tang e----*/

/*---- yang s----*/

Route::match('card/list', [Route::get], 'minipro.card@lists');

Route::match('member/show', [Route::get], 'minipro.member@show')->where('id', '\d+');
Route::match('member/update', [Route::post], 'minipro.Member@update');
Route::match('mch/cats_mch_limit', [Route::get], 'minipro.mch@catsMchLimit');

/*---- yang e----*/



