<?php namespace App\DataTypes;


use App\Exceptions\CardException;
use Libs\Route;

class CardTypes {

    const member_card = 'MEMBER_CARD';
    const groupon     = 'GROUPON';
    const cash        = 'CASH';
    const discount    = 'DISCOUNT';
    const general_coupon = 'GENERAL_COUPON';
    const gift        = 'GIFT';

    const date_type_range     = 'DATE_TYPE_FIX_TIME_RANGE';
    const date_type_fix_term  = 'DATE_TYPE_FIX_TERM';
    const date_type_permanent = 'DATE_TYPE_PERMANENT';


    public static function checkTypes($type)
    {
        if(!in_array($type, self::getTypes())){
            throw new CardException("不存在的卡券类型：" . $type);
        }
    }


    public static function getTypes(){
        return [self::member_card, self::groupon, self::cash, self::discount, self::general_coupon, self::gift];
    }

    public static function getDefindFields($type){
        return self::definedFields()[$type];
    }

    public static function getCanEditFields($type){
        return self::canEditFields()[$type];
    }

    private static function canEditFields()
    {
        return [
            self::member_card => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.notice',
                'base_info.description',
                'base_info.color',
                'base_info.service_phone',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
                'background_pic_url',
                'prerogative',
                'base_info.pay_info.swipe_card.is_swipe_card',
                'base_info.center_title',
                'base_info.center_sub_title',
                'base_info.center_url'
            ],
            self::groupon => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.notice',
                'base_info.description',
                'base_info.service_phone',
                'base_info.color',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
            ],
            self::cash => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.notice',
                'base_info.service_phone',
                'base_info.description',
                'base_info.color',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
            ],
            self::discount => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.notice',
                'base_info.service_phone',
                'base_info.description',
                'base_info.color',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
            ],
            self::general_coupon => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.notice',
                'base_info.service_phone',
                'base_info.description',
                'base_info.color',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
            ],
            self::gift => [
                'base_info.logo_url',
                'base_info.title',
                'base_info.service_phone',
                'base_info.notice',
                'base_info.description',
                'base_info.color',
                'base_info.date_info.type',
                'base_info.date_info.begin_timestamp',
                'base_info.date_info.end_timestamp',
            ]
        ];
    }

    private static function definedFields(){
        return [
            self::member_card       => [//如果模糊的提示缺少必要的json字段字段，请检查auto_activate对应的字段
                "supply_bonus"  => false,
                "supply_balance" => false,
                "activate_url"  => Route::named('cardActive'),
                "base_info" => [
                    "date_info"     =>  [
                        "type" => self::date_type_permanent
                    ],
                    "get_limit" => 1,
                    "pay_info"  => [
                        "swipe_card" => [
                            "is_swipe_card" => true
                        ]
                    ]
                ]
            ],
            self::groupon           => [
                "base_info" => [
                    "date_info" => [
                        "type" => self::date_type_range
                    ]
                ]
            ],
            self::cash              => [
                "base_info" => [
                    "date_info" => [
                        "type" => self::date_type_range
                    ]
                ]
            ],
            self::discount          => [
                "base_info" => [
                    "date_info" => [
                        "type" => self::date_type_range
                    ]
                ]
            ],
            self::general_coupon    => [
                "base_info" => [
                    "date_info" => [
                        "type" => self::date_type_range
                    ]
                ]
            ],
            self::gift              => [
                "base_info" => [
                    "date_info" => [
                        "type" => self::date_type_range
                    ]
                ]
            ]
        ];
    }

}

