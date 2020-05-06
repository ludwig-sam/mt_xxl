<?php namespace App\Service\Nav;


class NavConfig {

    const admin = [
        'merchantlist',//商户列表,
        'merchantadd',//添加商户,
        'merchantdetail',//商户详情,
        'merchantclassify',//分类列表,
        'classifyadd',//添加分类,
        'classifyEdit',//编辑分类,
        'labellist',//标签列表,
        'labeladd',//添加标签,
        'labeledit',//编辑标签,
        'wx_card',//微信会员卡,
        'wx_cardedit',//编辑会员卡,
        'gradelist',//会员等级,
        'gradecreate',//添加会员等级,**
        'gradeedit',//编辑会员等级,
        'memberlist',//会员列表,
        'memberdetail',//会员详情,
        'memberbill',//会员账单,
        'memberrecharge-record',//会员充值记录,**
        'conponslist',//优惠劵列表,
        'conponscreate',//优惠劵添加,**
        'recharge_record',//充值记录,**
        'integral',//积分明细,
        'put_money',//提现管理,**
        'put_money_operate',//提现审核,**
        'put_money_check',//提现查看,**
        'advert',//广告列表,
        'advertcreate',//添加广告,
        'advertedit',//广告编辑,
        'reply',//自动回复,
        'menu',//自定义菜单,
        'menu_create',//菜单添加,
        'menu_edit',//菜单编辑,
        'material',//素材管理,
        'material_create',//素材添加,
        'material_operate',//素材编辑,
        'fans',//粉丝管理,
        'my',//我的账号,
        'role',//角色,
        'role_create',//角色添加,
        'role_edit',//角色编辑
        'operate',//操作员,
        'operate_create',//操作员添加,
        'operate_edit',//操作员编辑,
        'wx_news',//微信消息模板,
        'log',//操作记录
        'hot',//活动管理
        'hot/create',//添加活动
        'hot/edit',//活动编辑
        'text_create',//文本素材添加
        'text_edit',//文本素材编辑
        'img_create',//图片添加
        'key_list',//关键字列表
        'key_edit',//关键字编辑
        'key_create',//创建关键字
        'index',//首页
        'collectionTotal',//收银统计
    ];

    const mch = [
        'stores_data',//门店概况,
        'stores_dataedit',//编辑概况,
        'stores_store',//门店列表
        'store_create',//门店添加,
        'store_edit',//门店编辑,
        'store_coupon',//优惠劵,
        'people',//收银员,
        'people_create',//收银员添加,
        'people_edit',//收银员编辑,
        'system',//收银系统,
        'system_create',//收银系统添加,
        'system_edit',//收银系统编辑,
        'bill',//账单数据,
        'carry',//资金提现,
        'carry_apply',//提现申请,**
        'carry_bank',//银行卡管理,**
        'carry_bank_create',//银行卡添加,**
        'carry_bank_edit',//银行卡编辑,**
        'my',//我的账号,
        'pay_config',//支付配置
    ];

    static function getAllNavs()
    {
        return array_merge(self::admin, self::mch);
    }


}