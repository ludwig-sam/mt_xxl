<?php

namespace Libs\Payments\Upay\Support;


use App\Exceptions\PayApiException;
use App\Exceptions\PayPaymentException;
use Libs\Arr;
use Libs\Log;
use Illuminate\Support\Collection;
use \Libs\Payments\Contracts\Config;

class Support extends \Libs\Payments\Helper\Support
{

    protected static $instance;

    protected $timeout = 20;

    public static function getInstance(Config $config)
    {
        if (!(self::$instance instanceof Support)) {
            self::$instance = new Support($config);
        }

        return self::$instance;
    }

    public static function getRequestId($devId)
    {
        return $devId . date('His');
    }

    public static function getTermRequestId($devId)
    {
        return $devId . date('His') . str_pad((microtime(true) % 1000), 4, "0", STR_PAD_LEFT);
    }

    public static function filterPayload($payload, Config $config)
    {
        if (isset($payload['notify_url'])) unset($payload['notify_url']);

        $payload['sign'] = self::generateSign($payload, $config->getKey());

        return $payload;
    }

    public static function requestApi($endpoint, $data, Config $config):Collection
    {
        Log::debug('Request To Upay Api', [$config->getBaseUri() . $endpoint, $data]);

        $ret    = self::getInstance($config)->post($endpoint, $data);
        $result = json_decode($ret, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new PayApiException('Upay API Error:' . $ret, PayApiException::api_error, $result);
        }

        if (!isset($result['code'])) {
            throw new PayApiException('Upay API Error:网络异常', PayApiException::api_error, $result);
        }

        if ($result['code'] != 100) {
            throw new PayApiException('Upay API Error:' . $result['message'], PayApiException::api_message, $result);
        }

        if ($result['sub_code'] != 100) {
            throw new PayApiException('Upay API Submessage:' . $result['sub_message'], PayApiException::api_message, $result);
        }

        Log::debug('Response Upay Api', $result);

        return new Collection($result);
    }


    public static function generateSign($payload, $key)
    {
        if (is_null($key)) {
            throw new PayPaymentException('Missing Upay Config -- [key]', PayPaymentException::miss_key);
        }
        $string = md5(self::getSignContent($payload) . '&key=' . $key);

        $string = strtoupper($string);

        return $string;
    }

    public static function getSignContent($payload)
    {
        $para_filter = self::paraFilter($payload);
        $para_sort   = self::argSort($para_filter);

        return self::createLinkstring($para_sort);
    }

    private static function paraFilter($para)
    {
        $para_filter = [];

        $para = Arr::filter($para, ["", null], true);

        foreach ($para as $key => $val) {
            if (in_array($key, ['sign', 'sign_type', 'sign_format'])) {
                continue;
            }

            $para_filter[$key] = $para[$key];
        }

        return $para_filter;
    }

    private static function argSort($para)
    {
        ksort($para);
        reset($para);

        return $para;
    }

    private static function createLinkstring($para)
    {
        $arg = [];
        foreach ($para as $key => $val) {
            $arg[] = $key . "=" . $val;
        }

        $str = join('&', $arg);

        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }

        return $str;
    }

}