<?php

namespace App\Service\NotifyUser;


use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use App\Exceptions\PayPaymentException;
use App\Http\Codes\Code;
use Libs\Response;
use App\Models\FansModel;
use App\Models\PayNotifyUserModel;

class NotifyUser
{

    private function fansModel()
    {
        return new FansModel();
    }

    private function model()
    {
        return new PayNotifyUserModel;
    }

    public function getFans($openid)
    {
        $fans_row   = $this->fansModel()->where('openid', $openid)->first();

        if(!$fans_row){
            throw new ExceptionCustomCodeAble('粉丝不存在', Code::not_exists);
        }

        return $fans_row;
    }

    function add($openid, $mch_id)
    {
        $fans_id = $this->getFansIdByOpenidCheck($openid);

        if($this->model()->getByFans($fans_id)){
            return true;
        }

        return $this->save([
            "fans_id" => $fans_id,
            "openid"  => $openid,
            'mch_id'  => (int)$mch_id
        ]);
    }

    public function getFansIdByOpenidCheck($openid)
    {
         $fans_id = $this->fansModel()->where('openid', $openid)->value('id');

         if(!$fans_id){
             throw new PayPaymentException("粉丝不存在", Code::not_exists);
         }

         return $fans_id;
    }

    public function save($data)
    {
        $affetch = $this->model()->create($data);

        return $affetch;
    }
}