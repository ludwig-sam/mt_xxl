<?php

namespace Libs\Payments\Wechat\Support;


use Libs\Payments\Helper\Exceptions\PayApiException;
use Libs\Payments\Helper\Exceptions\PayPaymentException;
use Illuminate\Support\Collection;
use Libs\Log;
use Libs\Payments\Contracts\Config;

class Support extends \Libs\Payments\Helper\Support
{

    protected static $instance;

    public static function getInstance(Config $config)
    {
        if (!(self::$instance instanceof Support)) {
            self::$instance = new Support($config);
        }

        return self::$instance;
    }

    public static function requestApi($endpoint, $data, Config $config):Collection
    {

        Log::debug('Request To Wechat Api', [$config->getBaseUri() . $endpoint, $data]);

        $ret = self::sendRequest($endpoint, $data, $config);

        $result_arr = self::fromXml($ret);

        $result = new Collection($result_arr);

        self::checkSuccess($result);

        self::checkSign($endpoint, $result, $config->getKey());

        return $result;
    }

    private static function sendRequest($endpoint, $data, Config $config)
    {
        $options = [];

        $xml = self::toXml($data);

        self::cert($options, $config);

        return self::getInstance($config)->post(
            $endpoint,
            $xml,
            $options
        );
    }

    private static function needCert(Config $config)
    {
        return $config->sslIsVerify();
    }

    private static function cert(&$options, Config $config)
    {
        if (self::needCert($config)) {
            $dir = resource_path('pem/mina');

            $options['cert']    = $dir . '/apiclient_cert.pem';
            $options['ssl_key'] = $dir . '/apiclient_key.pem';
        }
    }

    private static function checkSign($endpoint, Collection $result, $key)
    {
        if (strpos($endpoint, 'mmpaymkttransfers') !== false || self::generateSign($result->all(), $key) === $result->get('sign')) {
            return;
        }

        Log::warning('Wechat Sign Verify FAILED', $result->all());

        throw new \Exception('Wechat Sign Verify FAILED', 3);
    }

    private static function checkSuccess(Collection $result)
    {
        if (self::isSuccess($result)) {
            return;
        }

        throw new PayApiException(
            'Get Wechat API Error:' . $result->get('return_msg') . $result->get('err_code_des', ''),
            PayApiException::api_message,
            $result->all()
        );
    }

    private static function isSuccess(Collection $result)
    {
        return $result->get('return_code') == 'SUCCESS' && $result->get('result_code') == 'SUCCESS';
    }

    public static function generateSign($data, $key = null):string
    {
        if (is_null($key)) {
            throw new PayPaymentException('Missing Wechat Config -- [key]', PayPaymentException::miss_key);
        }

        ksort($data);

        $string = md5(self::getSignContent($data) . '&key=' . $key);

        return strtoupper($string);
    }


    public static function getSignContent($data):string
    {
        $buff = [];

        foreach ($data as $k => $v) {

            if (self::notSignValue($k, $v)) continue;

            $buff[] = $k . '=' . $v;
        }

        return join('&', $buff);
    }

    private static function notSignValue($k, $v)
    {
        if (in_array($k, ['sign', 'sign_type'])) return true;

        if ($v == '' || is_array($v)) return true;

        return false;
    }

    public static function toXml($data):string
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new PayApiException('Convert To Xml Error! Invalid Array!', PayApiException::data_convert_fail);
        }

        $xml = '<xml>';
        foreach ($data as $key => $val) {
            $xml .= is_numeric($val) ? '<' . $key . '>' . $val . '</' . $key . '>' :
                '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
        }
        $xml .= '</xml>';

        return $xml;
    }

    public static function fromXml($xml):array
    {
        if (is_array($xml)) return $xml;

        if (!$xml) {
            throw new PayApiException('Convert To Array Error! Invalid Xml!', PayApiException::data_convert_fail);
        }

        libxml_disable_entity_loader(true);

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }
}
