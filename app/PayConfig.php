<?php namespace App;


use App\Exceptions\PayPaymentException;

class PayConfig
{
    //官方支付
    const PAYMENT_WX_BAR_CODE     = 1;//微信刷卡支付方式
    const PAYMENT_ALIPAY_BAR_CODE = 2;//支付宝刷卡支付方式
    const PAYMENT_WX_JSAPI        = 3;//微信客户端支付方式
    const PAYMENT_WX_SCAN_CODE    = 4;//微信扫码支付
    const PAYMENT_WX_MINA         = 5;//微信小程序支付
    const PAYMENT_ALIPAY_H5       = 6;//支付宝h5服务商支付
    //中信银行支付
    const PAYMENT_CHBANK_WX_BAR_CODE     = 7; //超市-中行微信刷机支付
    const PAYMENT_ChBANK_ALIPAY_BAR_CODE = 9; //超市-中行支付宝刷卡支付
    const PAYMENT_CHBANK_CARD            = 10; //超市-中行支付宝刷卡支付
    //现金
    const PAYMENT_CASH    = 11;//现金支付
    const PAYMENT_POS     = 12;//POS支付
    const PAYMENT_BALANCE = 21;//POS支付

    //银联支付
    const PAYMENT_UPAY_WX_BAR_CODE      = 13; //银联微信刷卡支付
    const PAYMENT_UPAY_WX_JSAPI         = 14; //银联微信客户端支付
    const PAYMENT_UPAY_ALIPAY_BAR_CODE  = 15; //银联支付宝刷卡支付
    const PAYMENT_UPAY_ALIPAY_H5        = 16; //银联支付宝js支付
    const PAYMENT_UPAY_WX_SCAN_CODE     = 17; //银联微信C扫B
    const PAYMENT_UPAY_ALIPAY_SCAN_CODE = 18; //银联支宝C扫B

    const PAYMENTS = [
        self::PAYMENT_WX_BAR_CODE            => 'wx_pub_bar_code',
        self::PAYMENT_ALIPAY_BAR_CODE        => 'alipay_bar_code',
        self::PAYMENT_WX_JSAPI               => 'wx_pub_jsapi',
        self::PAYMENT_WX_SCAN_CODE           => 'wx_pub_scan_code',
        self::PAYMENT_WX_MINA                => 'wx_pub_mina',
        self::PAYMENT_ALIPAY_H5              => 'alipay_h5',
        self::PAYMENT_CHBANK_WX_BAR_CODE     => 'chbank_wx_pub_bar_code',
        self::PAYMENT_ChBANK_ALIPAY_BAR_CODE => 'chbank_alipay_bar_code',
        self::PAYMENT_CHBANK_CARD            => 'chbank_card',
        self::PAYMENT_CASH                   => 'cash',
        self::PAYMENT_POS                    => 'pos',
        self::PAYMENT_UPAY_WX_BAR_CODE       => 'upay_wx_pub_bar_code',
        self::PAYMENT_UPAY_WX_JSAPI          => 'upay_wx_pub_jsapi',
        self::PAYMENT_UPAY_ALIPAY_BAR_CODE   => 'upay_alipay_bar_code',
        self::PAYMENT_UPAY_ALIPAY_H5         => 'upay_alipay_h5',
        self::PAYMENT_UPAY_WX_SCAN_CODE      => 'upay_wx_scan_code',
        self::PAYMENT_UPAY_ALIPAY_SCAN_CODE  => 'upay_alipay_scan_code',
        self::PAYMENT_BALANCE                => 'balance_pay'
    ];

    const way_official    = 'OFFICAL';
    const way_upay        = 'UPAY';
    const way_specail     = 'SPECIAL';
    const way_supermarket = 'SUPERMARKET';

    const AUTH_CODE_RULES = [
        "wx"      => "/^1[012345]\d{16}$/",
        "alipay"  => "/^(2[56789]|30)\d{14,22}$/",
        'balance' => "/^3\d{8,18}$/",
    ];

    const AUTH_CODE_CHANNEL = [
        self::way_official    => [
            'wx'      => PayConfig::PAYMENT_WX_BAR_CODE,
            'alipay'  => PayConfig::PAYMENT_ALIPAY_BAR_CODE,
            'balance' => PayConfig::PAYMENT_BALANCE,
        ],
        self::way_upay        => [
            'wx'      => PayConfig::PAYMENT_UPAY_WX_BAR_CODE,
            'alipay'  => PayConfig::PAYMENT_UPAY_ALIPAY_BAR_CODE,
            'balance' => PayConfig::PAYMENT_BALANCE,
        ],
        self::way_supermarket => [
            'wx'      => PayConfig::PAYMENT_UPAY_WX_BAR_CODE,
            'alipay'  => PayConfig::PAYMENT_ChBANK_ALIPAY_BAR_CODE,//中行支付宝刷卡
            'balance' => PayConfig::PAYMENT_BALANCE,
        ],
    ];

    public static function isNotNeedConfigChennel($way)
    {
        return in_array($way, [
            self::way_specail,
            self::way_supermarket
        ]);
    }

    public static function isSystemCode($authCode)
    {
        $code_type = self::matchCodeType($authCode);

        return in_array($code_type, ['balance']);
    }

    public static function formatWay($way)
    {
        $way = strtoupper($way);

        if (!isset(self::AUTH_CODE_CHANNEL[$way])) {
            throw  new PayPaymentException("未定义支付通道: " . $way, PayPaymentException::undefined_way);
        }

        return $way;
    }

    public static function matchCodeType($authCode)
    {
        foreach (self::AUTH_CODE_RULES as $payment => $preg) {
            if (preg_match($preg, $authCode)) {
                return $payment;
            }
        }

        return null;
    }

    public static function matchByAuthCode($way, $authCode)
    {
        $way       = self::formatWay($way);
        $payments  = self::AUTH_CODE_CHANNEL[$way];
        $code_type = self::matchCodeType($authCode);

        if (!$code_type) {
            throw  new PayPaymentException("无效的支付码: " . $authCode, PayPaymentException::invalid_auth_code);
        }

        $payment_id = $payments[$code_type];

        return self::PAYMENTS[$payment_id];
    }

}