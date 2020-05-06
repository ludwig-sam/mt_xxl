<?php

use Libs\Route;

/*---- ygl s-----*/

//商户分类
Route::match('category/add', [Route::post], 'admin.mchCategory@add');
Route::match('category/show/{id}', [Route::get], 'admin.mchCategory@show')->where('id', '\d+');
Route::match('category/update', [Route::post], 'admin.mchCategory@update');
Route::match('category/delete/{id}', [Route::get], 'admin.mchCategory@delete')->where('id', '\d+');
//标签
Route::match('tag/add', [Route::post], 'admin.tags@add');
Route::match('tag/show/{id}', [Route::get], 'admin.tags@show')->where('id', '\d+');
Route::match('tag/update', [Route::post], 'admin.tags@update');
Route::match('tag/list', [Route::get], 'admin.tags@list');
//商户
Route::match('mch/add', [Route::post], 'admin.mch@add');
Route::match('mch/update', [Route::post], 'admin.mch@update');
Route::match('mch/lists', [Route::get], 'admin.mch@lists');
Route::match('mch/levels', [Route::get], 'admin.mch@level');
Route::match('mch/delete/{id}', [Route::get], 'admin.mch@delete')->where('id', '\d+');
Route::match('mch/show/{id}', [Route::get], 'admin.mch@show')->where('id', '\d+');
Route::match('mch/card_lists', [Route::get], 'admin.mch@cardList');


//权限
Route::match('role_lists', [Route::get], 'admin.role@roleLists');
Route::match('permissions_list', [Route::get], 'admin.role@permissionsList');
Route::match('role_add', [Route::post], 'admin.role@add');
Route::match('role_update', [Route::post], 'admin.role@update');
Route::match('role_show/{id}', [Route::get], 'admin.role@show')->where('id', '\d+');
Route::match('role_delete/{id}', [Route::get], 'admin.role@delete')->where('id', '\d+');
Route::match('role_chStatus', [Route::post], 'admin.role@chStatus');

//账户
Route::match('me', [Route::get], 'admin.admin@getMe');
Route::match('me_update', [Route::post], 'admin.admin@update');

/*---- ygl e-----*/


/*---- tangweiwei s----*/
//广告
Route::match('advert/create', [Route::post], 'admin.advert@create')->name('create_advert');
Route::match('advert_list', [Route::get], 'admin.advert@advertList');
Route::match('advert/show/{id}', [Route::get], 'admin.advert@getAdvert');
Route::match('advert/update', [Route::post], 'admin.advert@update');
Route::match('advert/delete/{id}', [Route::get], 'admin.advert@delete');

Route::match('advert_position', [Route::get], 'admin.advertPosition@lists');


//会员
Route::match('member/list', [Route::get], 'admin.member@lists');
Route::match('member/show/{id}', [Route::get], 'admin.member@show')->where('id', '\d+');
Route::match('member/update', [Route::post], 'admin.member@updateOne')->where('id', '\d+');

//操作列表
Route::match('opration_log/lists', [Route::get], 'admin.oprationLog@lists');
Route::match('opration_log/export_select', [Route::get, Route::post], 'admin.oprationLog@exportSelect');
Route::match('opration_log/export_filter', [Route::get, Route::post], 'admin.oprationLog@exportFilter');


Route::match('member/profession', [Route::get], 'admin.profession@lists');

Route::match('message_send_log', [Route::get], 'admin.messageSendLog@lists');

Route::match('member/pay_order/{id}', [Route::get], 'admin.member@memberPayOrder');

Route::match('keyword_list', [Route::get], 'admin.replyKeywords@keywordList');

Route::match('mch/set_hot', [Route::post], 'admin.mch@setHot');

//活动
Route::match('activity/create', [Route::post], 'admin.activity@create');
Route::match('activity/list', [Route::get, Route::post], 'admin.activity@activityList');
Route::match('activity/show/{id}', [Route::get, Route::post], 'admin.activity@show');
Route::match('activity/update', [Route::post], 'admin.activity@update');
Route::match('activity/delete/{id}', [Route::get, Route::post], 'admin.activity@delete');

//商户列表导出
Route::match('mch/export_select', [Route::get, Route::post], 'admin.mch@exportSelect');
Route::match('mch/export_filter', [Route::get, Route::post], 'admin.mch@exportFilter');

//积分列表导出
Route::match('point_log/export_select', [Route::get, Route::post], 'admin.memberAccountLog@exportSelect');
Route::match('point_log/export_filter', [Route::get, Route::post], 'admin.memberAccountLog@exportFilter');

/*---- tangweiwei e----*/


/*---- li s----*/

Route::match('home', [Route::post, Route::get], 'admin.homes@index');
Route::match('collection_total', [Route::get], 'admin.homes@collectionTotal');


//微信
Route::match('wechat/card_create', [Route::post], 'admin.wechatCard@create');
Route::match('wechat/update/{id}', [Route::post], 'admin.wechatCard@update');
Route::match('wechat/get/{id}', [Route::get], 'admin.wechatCard@get');
Route::match('wechat/upload_image', [Route::post], 'admin.wechatMedia@uploadImage');

//微信模版消息
Route::match('wx_template/list', [Route::get], 'admin.wechatTemplate@list');


//分类
Route::match('category/list', [Route::get], 'admin.mchCategory@list');
Route::match('category/ch_status', [Route::post], 'admin.mchCategory@chStatus');

//导航
Route::match('nav/ch_nav', [Route::post], 'admin.Nav@chNav');

//会员
Route::match('member/send_card', [Route::post, Route::get], 'admin.member@sendCardSelect');
Route::match('member/send_card_filter', [Route::post, Route::get], 'admin.member@sendCardFilter');
Route::match('member/send_template_select', [Route::post, Route::get], 'admin.member@sendTemplateSelect');
Route::match('member/send_template_filter', [Route::post, Route::get], 'admin.member@sendTemplateFilter');
Route::match('member/export_select', [Route::get, Route::post], 'admin.member@exportSelect');
Route::match('member/export_filter', [Route::get, Route::post], 'admin.member@exportFilter');

//积分
Route::match('member/point_log', [Route::get, Route::post], 'admin.memberAccountLog@pointLog');


//素材
Route::match('material/create/{type}', [Route::post], 'admin.material@create');
Route::match('material/upload_thumb', [Route::post], 'admin.material@uploadThumb');
Route::match('material/get/{id}', [Route::post, Route::get], 'admin.material@get');
Route::match('material/update/{id}', [Route::post], 'admin.material@update');
Route::match('material/limit/{type}', [Route::get], 'admin.material@limit');
Route::match('material/pull_wechat/{type}', [Route::get], 'admin.material@pullWechat');
Route::match('material/delete/{id}', [Route::get], 'admin.material@delete');

//卡券
Route::match('card/list', [Route::get, Route::post], 'admin.card@list');
Route::match('card/modify_stock/{id}', [Route::get, Route::post], 'admin.wechatCard@modifyStock');
Route::match('card/qrcode/{id}', [Route::get, Route::post], 'admin.card@qrcode');
Route::match('card/use_status/{id}', [Route::post], 'admin.card@useStatus');
Route::match('card/exchange_list', [Route::get, Route::post], 'admin.card@exchangeList');
Route::match('card/delete/{id}', [Route::get], 'admin.card@delete');

//奖励
Route::match('reward/get', [Route::get], 'admin.reward@get');
Route::match('reward/set', [Route::post], 'admin.reward@set');

//自动回复
Route::match('reply/create', [Route::get, Route::post], 'admin.reply@create');
Route::match('reply/create_keywords', [Route::get, Route::post], 'admin.reply@createKeywords');
Route::match('reply/update/{id}', [Route::get, Route::post], 'admin.reply@update');
Route::match('reply/get/{id}', [Route::get, Route::post], 'admin.reply@get');
Route::match('reply/get_event/{event}', [Route::get, Route::post], 'admin.reply@getByEvent');
Route::match('reply/stop/{id}', [Route::get, Route::post], 'admin.reply@stopUse');
Route::match('reply/delete/{id}', [Route::get, Route::post], 'admin.reply@delete');


//消息
Route::match('message_config/tempalte_init', [Route::post], 'admin.templateConfig@tempalteInit');
Route::match('message_config/template_list', [Route::get], 'admin.templateConfig@templateList');
Route::match('message_config/template_get/{id}', [Route::get], 'admin.templateConfig@templateGet');
Route::match('message_config/template_update/{id}', [Route::post], 'admin.templateConfig@templateUpdate');

//粉丝
Route::match('fans/update', [Route::get], 'admin.fans@update');
Route::match('fans/update_by_openid', [Route::post], 'admin.fans@updateByOpenid');
Route::match('fans/list', [Route::get, Route::post], 'admin.fans@list');


//支付通知
Route::match('pay_notify/add', [Route::post], 'admin.payNotify@addUser');
Route::match('pay_notify/remove/{id}', [Route::get], 'admin.payNotify@removeUser');
Route::match('pay_notify/notify_user', [Route::get, Route::post], 'admin.payNotify@notifyUser');

//微信菜单
Route::match('wechat_menu/add', [Route::get, Route::post], 'admin.wechatMenu@add');
Route::match('wechat_menu/update/{id}', [Route::get, Route::post], 'admin.wechatMenu@update');
Route::match('wechat_menu/list', [Route::get, Route::post], 'admin.wechatMenu@list');
Route::match('wechat_menu/refresh/{id}', [Route::get, Route::post], 'admin.wechatMenu@refresh');
Route::match('wechat_menu/sort/{id}', [Route::get, Route::post], 'admin.wechatMenu@sort');
Route::match('wechat_menu/delete/{id}', [Route::get, Route::post], 'admin.wechatMenu@delete');

//会员等级
Route::match('member_level/get/{id}', [Route::get, Route::post], 'admin.memberLevel@get');
Route::match('member_level/update/{id}', [Route::post], 'admin.memberLevel@update');
Route::match('member_level/list', [Route::get], 'admin.memberLevel@list');

//操作员
Route::match('oprator/get/{id}', [Route::get], 'admin.oprator@get');
Route::match('oprator/add', [Route::post], 'admin.oprator@add');
Route::match('oprator/update/{id}', [Route::post], 'admin.oprator@update');
Route::match('oprator/list', [Route::get, Route::post], 'admin.oprator@list');
Route::match('oprator/ch_status/{id}', [Route::post], 'admin.oprator@chStatus');
Route::match('oprator/delete/{id}', [Route::get], 'admin.oprator@delete');

//商户
Route::match('mch/get_all', [Route::get], 'admin.mch@getAll');
Route::match('mch/stop/{id}', [Route::get], 'admin.mch@stop');

//商户提现审核
Route::match('withdraw/bill/count', 'get', 'admin.withdraw@count');
Route::match('withdraw', 'get', 'admin.withdraw@limit');
Route::match('withdraw/{id}', 'put', 'admin.withdraw@update')->where('id', '\d+');
Route::match('withdraw/{id}', 'get', 'admin.withdraw@get')->where('id', '\d+');
Route::match('withdraw/export', 'get', 'admin.withdraw@export');
Route::match('withdraw/export_select', 'get', 'admin.withdraw@exportSelect');

//虚拟卡
Route::match('fictitious', 'post', 'admin.fictitiousCard@create');
Route::match('fictitious', 'get', 'admin.fictitiousCard@limit');
Route::match('fictitious/{id}', 'get', 'admin.fictitiousCard@get')->where('id', '\d+');
Route::match('fictitious/{id}', 'put', 'admin.fictitiousCard@update')->where('id', '\d+');
Route::match('fictitious/{id}', 'delete', 'admin.fictitiousCard@delete')->where('id', '\d+');
Route::match('fictitious/code', 'get', 'admin.fictitiousCardCode@limit');
Route::match('fictitious/code/export', 'get', 'admin.fictitiousCardCode@export');
Route::match('fictitious/code/export_select', 'get', 'admin.fictitiousCardCode@exportSelect');

//充值
Route::match('recharge', 'get', 'admin.recharge@limit');
Route::match('recharge/count', 'get', 'admin.recharge@count');
Route::match('recharge/export', 'get', 'admin.recharge@export');
Route::match('recharge/export_select', 'get', 'admin.recharge@exportSelect');