<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午10:38
 */

namespace App\Exceptions\Contracts;


use App\Http\Codes\Code;

class ExceptionCustomCodeAble extends \Exception
{
    public $row;
    private $customCode;

    public function __construct(string $message = "", $code = "", Array $row = [])
    {
        $this->row = $row;
        $this->customCode = $code === "" ? Code::fail : (is_array($code) ? 'array' : $code);
        parent::__construct( $message, intval($code));
    }

    public function getCustomCode()
    {
        return $this->customCode;
    }

    public function getRow()
    {
        return $this->row;
    }
}