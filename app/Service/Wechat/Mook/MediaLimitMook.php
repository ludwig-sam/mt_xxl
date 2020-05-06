<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/1
 * Time: 下午1:46
 */

namespace App\Service\Wechat\Mook;


class MediaLimitMook
{

    private $total_count = 2;

    public function getLimitImage($start, $number)
    {

        $arr         = [];
        $item        = [
            "media_id" => "media_12212321",
            "name" => "test1",
            "update_time" => time(),
            "url" => "http://oxndwbhg7.bkt.clouddn.com/1532855304gkRE2H.jpeg"
        ];


        for($i=0; $i < $this->total_count; $i++){
            $item['media_id'] = "media_image_" . $i;
            $arr[]            = $item;
        }

        $items = array_slice($arr, $start, $number);

        return [
            'total_count' => $this->total_count,
            'item_count'  => count($items),
            'item' => $items
        ];
    }

    public function getLimitVideo($start, $number)
    {

        $arr         = [];
        $item        = [
            "media_id" => "media_12212321",
            "name" => "test1",
            "update_time" => time(),
            "url" => "http://oxndwbhg7.bkt.clouddn.com/1532855304gkRE2H.jpeg"
        ];


        for($i=0; $i < $this->total_count; $i++){
            $item['media_id'] = "media_video" . $i;
            $arr[]            = $item;
        }

        $items = array_slice($arr, $start, $number);

        return [
            'total_count' => $this->total_count,
            'item_count'  => count($items),
            'item' => $items
        ];
    }

    public function getLimitMusic($start, $number)
    {

        $arr         = [];
        $item        = [
            "media_id" => "media_12212321",
            "name" => "test1",
            "update_time" => time(),
            "url" => "http://oxndwbhg7.bkt.clouddn.com/1532855304gkRE2H.jpeg"
        ];


        for($i=0; $i < $this->total_count; $i++){
            $item['media_id'] = "media_music" . $i;
            $arr[]            = $item;
        }

        $items = array_slice($arr, $start, $number);

        return [
            'total_count' => $this->total_count,
            'item_count'  => count($items),
            'item' => $items
        ];
    }

    public function getLimitNews($start, $number)
    {
        $arr         = [];
        $item        = [
            "media_id" => "media_12212321",
            "content" => [
                "news_item" => [
                    [
                        "title"  => "mook",
                        "thumb_media_id" =>  "thumb_media_id",
                        "show_cover_pic" => "http://oxndwbhg7.bkt.clouddn.com/1532855304gkRE2H.jpeg",
                        "author" => "AUTHOR",
                        "digest" => "DIGEST",
                        "content" => "CONTENT",
                        "url" => "URL",
                        "content_source_url" => "http:www.baidu.com"
                    ]
                ]
            ],
            "update_time" => time(),
        ];


        for($i=0; $i < $this->total_count; $i++){
            $item['media_id'] = "media_video" . $i;
            $arr[]            = $item;
        }

        $items = array_slice($arr, $start, $number);

        return [
            'total_count' => $this->total_count,
            'item_count'  => count($items),
            'item' => $items,
        ];
    }
}