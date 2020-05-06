<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/15
 * Time: 下午1:18
 */

namespace App\Service\MchWithdraw;


use App\Models\MchBankCardModel;
use Providers\Curd\CurdServiceTrait;
use App\Service\Row\MchBanckCardRow;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;

class MchBankCardService extends Service
{

    use CurdServiceTrait;


    public function model():MchBankCardModel
    {
        return $this->newSingle(MchBankCardModel::class);
    }

    public function checkCanDelete(MchBanckCardRow $banckCardRow)
    {
        return true;
    }

    public function createFill(UserAbstraict $admin, Collection $request)
    {
        $request->offsetSet('mch_id', $admin->getMchId());
        return $request;
    }


}