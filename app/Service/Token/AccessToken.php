<?php namespace App\Service\Token;

use App\Exceptions\TokenException;
use Libs\Str;
use Illuminate\Support\Facades\Redis;
use App\Service\Service;

class AccessToken extends  Service{
    public $expires_in = 7200;
    private $uniqueKey = 'mt_pay_unique_access_token';


    public function __construct($expiresIn = null, $uniqueKey = null)
    {
        if(!is_null($expiresIn)){
            $this->expires_in = $expiresIn;
        }

        if(!is_null($uniqueKey)){
            $this->uniqueKey = $uniqueKey;
        }
    }

    public function build(Array $arr){
        $cacheKey    = 'mt_access_token:' . join('_', $arr);
        return $this->setAccessToken( $cacheKey );
    }

    public function getExpires(){
        return $this->expires_in;
    }

    private function getUnique($checkExist, $cacheKey){
        $accessToken = md5( uniqid(rand()) . md5( $cacheKey) ) . Str::rand(30);
        if($checkExist) {
            if(in_array($accessToken, $checkExist)) {
                return $this->getUnique($checkExist, $cacheKey);
            }
        }
        return $accessToken;
    }

    private function setKeyAndTokenRelation($cacheKey, $accessToken){
        Redis::set($cacheKey, $accessToken);
    }

    private function getKeyAndTokenRelation($cacheKey){
        return Redis::get($cacheKey);
    }

    private function setUniquePool(Array $uniquePool){
        Redis::del($this->uniqueKey);
        if($uniquePool)Redis::sadd($this->uniqueKey, $uniquePool);
    }

    public function getUniquePool(){
        return Redis::smembers($this->uniqueKey) ? : [];
    }

    private function delIfExists($cacheKey, &$checkExist){
        $accessToken = $this->getKeyAndTokenRelation($cacheKey);

        if($accessToken){
            foreach ($checkExist as $key => $val) {
                if($val == $accessToken) {
                    unset($checkExist[$key]);
                }
            }
            Redis::del($accessToken);
        }
    }

    public function setAccessToken($cacheKey) {

        $uniquePool = $this->getUniquePool();

        $accessToken = $this->getUnique($uniquePool, $cacheKey);

        $this->delIfExists($cacheKey, $uniquePool);

        $uniquePool[] = $accessToken;

        $this->setUniquePool($uniquePool);

        Redis::set($accessToken, $cacheKey);
        Redis::expire($accessToken, $this->expires_in);

        $this->setKeyAndTokenRelation($cacheKey, $accessToken);

        return $accessToken;
    }

    public function verify($accessToken){

        if(!$accessToken){
            throw new TokenException("miss access_token", TokenException::miss_access_token);
        }

        if(!$infoString = Redis::get($accessToken)){
            throw new TokenException("invalid access_token", TokenException::token_expire);
        }

        $validStr = Str::last($infoString, ':');

        return explode('_', $validStr);
    }
    
}