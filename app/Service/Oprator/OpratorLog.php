<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/13
 * Time: 下午4:50
 */

namespace App\Service\Oprator;


use App\Models\OprationLogModel;
use App\Service\Users\AdminUser;

class OpratorLog
{
    public static function note($title, $detail)
    {
        $user_id       = AdminUser::getInstance()->getId();
        $oprator_model = new OprationLogModel();

        $data = [
            'user_id' => $user_id,
            'title'   => $title,
            'detial'  => $detail
        ];

        $oprator_model->create($data);
    }
}