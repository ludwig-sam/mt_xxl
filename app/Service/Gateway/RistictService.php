<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway;


use App\Exceptions\GatewayException;
use App\Http\Codes\Code;
use App\Service\Gateway\Contracts\RistrictInterface;

class RistictService implements RistrictInterface
{

    private $ristricts = [];

    public function registe(RistrictInterface $ristrict)
    {
        $class = get_class($ristrict);

        $this->ristricts[$class] = $ristrict;
    }

    /**
     * @param $ristrict
     * @return RistrictInterface
     */
    private function toInterfce($ristrict)
    {
        return $ristrict;
    }

    public function isPass()
    {
        foreach ($this->ristricts as  $ristrict){
            if(!$this->toInterfce($ristrict)->isPass()){
                return false;
            }
        }

        return true;
    }

    public function pass()
    {
        if(!$this->isPass()){
            throw new GatewayException("访问被限制，请联系管理员", Code::gateway_not_pass);
        }
    }

}