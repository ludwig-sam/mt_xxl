<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/10
 * Time: 下午9:02
 */

namespace App\Service\Pay\PayWay;


use Libs\Payments\Wechat;
use App\PayConfig;
use App\Service\Pay\Contracts\PayWayAbstracts;
use Illuminate\Support\Collection;

class OfficialWay extends PayWayAbstracts
{

    public function way()
    {
        return PayConfig::way_official;
    }

    public static function getWxParam($wx)
    {
        if(!$wx){
            return [];
        }

        $wxCollection = new Collection($wx);

        $mode       = $wxCollection->get('mode');
        $app_id     = $wxCollection->get('app_id');
        $mch_id     = $wxCollection->get('mch_id');
        $key        = $wxCollection->get('key');
        $app_secret = $wxCollection->get('app_secret');
        $sub_mch_id = $wxCollection->get('sub_mch_id');
        $sub_app_id = $wxCollection->get('sub_app_id');
        $ssl_cert   = $wxCollection->get('ssl_cert') ? : null;
        $ssl_key    = $wxCollection->get('ssl_key') ? : null;

        $param = [
            'mode'       => $mode,
            'app_id'     => $app_id,
            'mch_id'     => $mch_id,
            'key'        => $key,
            'app_secret' => $app_secret,
            'ssl_cert'   => $ssl_cert,
            'ssl_key'   => $ssl_key,
        ];

        if ($mode === Wechat::MODE_SERVICE) {
            $param['sub_mch_id'] = $sub_mch_id;
            $param['sub_app_id'] = $sub_app_id;
        }

        return $param;
    }

    /**
     * 通过授权获取
     * @param $ali
     * @return array
     */
    public static function getAliParam($ali)
    {
        if(!$ali){
            return [];
        }

        $aliCollection = new Collection($ali);
    }

    public function param(Collection $collection)
    {
        return [
            'wx'  => self::getWxParam($collection->get('wx')),
            'ali' => self::getAliParam($collection->get('ali'))
        ];
    }
}