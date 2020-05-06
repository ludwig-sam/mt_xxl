<?php namespace App\DataTypes;


use App\Exceptions\ParamException;
use App\Http\Codes\Code;

class WechatMenuTypes{

    const type_click = 'click';
    const type_view  = 'view';
    const type_miniprogram = 'miniprogram';
    const type_scancode_waitmsg = 'scancode_waitmsg';
    const type_scancode_push = 'scancode_push';
    const type_pic_sysphoto = 'pic_sysphoto';
    const type_pic_photo_or_album = 'pic_photo_or_album';
    const type_pic_weixin = 'pic_weixin';
    const type_location_select = 'location_select';
    const type_media_id = 'media_id';
    const type_view_limited = 'view_limited';


    static function getTypes()
    {
        return [
            self::type_click,
            self::type_view,
            self::type_miniprogram,
            self::type_scancode_waitmsg,
            self::type_scancode_push,
            self::type_pic_sysphoto,
            self::type_pic_photo_or_album,
            self::type_pic_weixin,
            self::type_location_select,
            self::type_media_id,
            self::type_view_limited
        ];
    }

    static function check($type)
    {
        if(!in_array($type, self::getTypes())){
            throw new ParamException("未定义的菜单类型:" . $type, Code::not_exists);
        }
    }

}

