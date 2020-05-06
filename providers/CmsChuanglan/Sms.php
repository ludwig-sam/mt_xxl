<?php namespace Providers\CmsChuanglan;

use Abstracts\SmsInterface;

class Sms implements SmsInterface {
    private $api;
    private $response;

    public function __construct($account, $password, $host)
    {
        $this->api = new Api(compact('account', 'password', 'host'));
    }

    private function replacePlaceholder(&$tpl, $parmas)
    {
        foreach ($parmas as $palceholder => $val){
            $tpl = str_replace('{'.$palceholder.'}', $val , $tpl);
        }
    }

    private function parseResult($ret)
    {
        $result   = json_decode($ret, true);

        if(json_last_error() != JSON_ERROR_NONE){
            $this->response = '{"code" : "JSON_ERROR_NO"'.json_last_error().'}';
            return false;
        }

        if(isset($result['code'])  && $result['code']=='0'){
            return true;
        }

        return false;
    }

    private function sendSms($number, $msg)
    {
        $this->response = $this->api->sendSMS($number, $msg);

        return $this->parseResult($this->response);
    }

    public function getResponse()
    {
        return json_decode($this->response, true);
    }

    public function getRequest()
    {
        return $this->api->reqFields;
    }

    public function send($number, $sign, $msg, Array $params)
    {
        $msg = $sign . $msg;

        $this->replacePlaceholder($msg, $params);

        return $this->sendSms($number, $msg);
    }
}