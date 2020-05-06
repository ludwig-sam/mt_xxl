<?php

namespace App\Service\Account;


use App\Exceptions\CardException;
use App\Models\FictitiousCardModel;
use App\Service\Row\FictitousCardRow;
use Providers\Curd\CurdServiceTrait;
use App\Service\Service;

class FictitiousCardService extends Service
{

    use CurdServiceTrait;

    public function model():FictitiousCardModel
    {
        return $this->newSingle(FictitiousCardModel::class);
    }

    public function modifyStock(FictitousCardRow $card, $stock)
    {
        $old_stock = $card->stock();
        $stock     = intval($stock);
        $inc_stock = max(0, $stock - $old_stock);
        $dec_stock = max(0, $old_stock - $stock);
        $quantity  = $card->quantity() + ($stock - $old_stock);

        if ($stock < 0) {
            throw new CardException('库存不能小于0');
        }

        $code_service = new FictitiousCardCodeService();
        $codes        = $code_service->genarateCodesWithPwd($inc_stock);

        $code_service->saveNewCode($card->id(), $codes);
        $dec_stock && $code_service->model()->deleteNotUseCode($dec_stock);

        $card->getRow()->update(compact('quantity', 'stock'));
    }
}