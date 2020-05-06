<?php

namespace App\Service\Account;


use App\Exceptions\CardException;
use App\Models\FictitiousCardCodeModel;
use App\Service\Row\FictitousCardRow;
use Libs\Str;
use Providers\Curd\CurdServiceTrait;
use App\Service\Service;

class FictitiousCardCodeService extends Service
{

    use CurdServiceTrait;

    public function model():FictitiousCardCodeModel
    {
        return $this->newSingle(FictitiousCardCodeModel::class);
    }

    public function getCode($code, $pwd)
    {
        $row = $this->model()->getByPwd($code, $pwd);

        if (!$row) {
            throw new CardException('卡号或密码错误');
        }

        return $row;
    }

    public function genarateCode($card_id)
    {
        $card      = new FictitousCardRow($card_id);
        $code_pwds = $this->genarateCodesWithPwd($card->stock());

        $this->saveNewCode($card_id, $code_pwds);
    }

    public function saveNewCode($card_id, $code_pwds)
    {
        foreach ($code_pwds as $code_pwd) {
            $code = $code_pwd['code'];
            $pwd  = $code_pwd['pwd'];
            $this->model()->createNew([
                'card_id'  => $card_id,
                'password' => $pwd,
                'code_no'  => $code
            ]);
        }
    }

    public function genarateCodesWithPwd($len)
    {
        $tmp_codes = [];
        $result    = [];
        $pwd_len   = 6;
        $code_len  = 10;
        $num_set   = range(0, 9);
        while ($len > count($tmp_codes)) {

            $code        = Str::rand($code_len, $num_set);
            $pwd         = Str::rand($pwd_len, $num_set);
            $tmp_codes[] = $code;
            $result[]    = [
                'code' => $code,
                'pwd'  => $pwd,
            ];

            $tmp_codes = array_unique($tmp_codes);
        }

        return array_slice($result, 0, count($tmp_codes));
    }

    public function cardToUsed(FictitousCardRow $card, $code)
    {
        FictitiousCardCheck::checkTermOfValidity($card);
        FictitiousCardCheck::checkCardCanUse($card);
        FictitiousCardCheck::checkCodeCanUse($code);

        $ret = $this->model()->toUsed($code->id);
        $card->model()->decStock($card->id());

        return $ret;
    }

}