<?php namespace App\DataTypes;



use App\DataTypes\CardCodeStatus;
use App\Exceptions\CardException;

class CardStatus {

    const sending = 'SENDING';
    const disabled = 'DISABLED';
    const normal   = 'NORMAL';


    public static function getStatus()
    {
        return [self::sending, self::disabled];
    }

    public static function checkStatus(&$staus)
    {
        $staus = strtoupper($staus);

        if(!in_array($staus, self::getStatus())){
            throw new CardException("卡券状态不存在");
        }
    }

    public static function codeStatus($card_row, $code_row)
    {
        if($card_row->deleted_at){
            return CardCodeStatus::deleted;
        }

        switch ($card_row->status){
            case self::disabled:
                return CardCodeStatus::disabled;
        }

        switch ($code_row->status){
            case CardCodeStatus::consume:
                return $card_row->status;
                break;
        }

        if($code_row->end_time <= time()){
            return CardCodeStatus::expire;
        }

        return CardCodeStatus::normal;
    }


}

