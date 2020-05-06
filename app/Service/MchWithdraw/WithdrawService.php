<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/15
 * Time: 下午1:18
 */

namespace App\Service\MchWithdraw;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Models\MchWithdrawModel;
use Providers\Curd\CurdServiceTrait;
use App\Service\Service;
use App\Service\Row\MchBanckCardRow;
use App\Service\Users\Contracts\UserAbstraict;
use Illuminate\Support\Collection;
use Libs\Str;


class WithdrawService extends Service
{

    use CurdServiceTrait;


    public function model():MchWithdrawModel
    {
        return $this->newSingle(MchWithdrawModel::class);
    }

    public function mergeBanckInfo(Collection $request)
    {
        $banck_card_id  = $request->get('bank_card_id');
        $banck_card_row = new MchBanckCardRow($banck_card_id);
        $request        = $request->merge($banck_card_row->toArray());

        return $request;
    }

    public function createFill(Collection $request, UserAbstraict $admin)
    {
        $request = $request->merge([
            'order_no'      => Str::rand(30),
            'apply_user_id' => $admin->getId()
        ]);

        return $request;
    }

    public function disposeFill(Collection $request, UserAbstraict $admin)
    {
        $request = $request->merge([
            'oprator_user_id' => $admin->getId()
        ]);

        return $request;
    }

    public function disposeCheck($row, Collection $request)
    {
        if ($row->apply_money < $request->get('dispose_money')) {
            throw new ExceptionCustomCodeAble('处理金额不能大于申请金额');
        }
    }

}