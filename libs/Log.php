<?php namespace Libs;


use Abstracts\SmsInterface;
use Illuminate\Http\Request;
use Monolog\Logger;

class Log
{
    static private $_topic;

    static public function getTopic()
    {
        return self::$_topic;
    }

    static public function topic($topic)
    {
        self::$_topic = $topic;
        return new self;
    }

    static function sms():SmsInterface
    {
        return app(SmsInterface::class);
    }

    static private function write($level, $message, $context)
    {
        $context = toArray($context);

        try {
            \Log::log($level, $message, $context);
        } catch (\Exception $exception) {
            self::sms()->send(13127503298, config('sms.sign'), '阿里云日志异常，可能欠费', []);
        }
    }

    static public function info($message, $context = array())
    {
        self::write(Logger::INFO, $message, $context);
    }

    static public function error($message, $context = array())
    {
        self::write(Logger::ERROR, $message, $context);
    }

    static public function emergency($message, $context = array())
    {
        self::write(Logger::EMERGENCY, $message, $context);
    }

    static public function debug($message, $context = array())
    {
        self::write(Logger::DEBUG, $message, $context);
    }

    static public function critical($message, $context = array())
    {
        self::write(Logger::CRITICAL, $message, $context);
    }

    static public function alert($message, $context = array())
    {
        self::write(Logger::ALERT, $message, $context);
    }

    static public function notice($message, $context = array())
    {
        self::write(Logger::NOTICE, $message, $context);
    }

    static public function warning($message, $context = array())
    {
        self::write(Logger::WARNING, $message, $context);
    }

    static public function request(Request $request, $params = [])
    {
        $data = ['request' => $request->all(), 'method' => $request->method(), 'route' => str_replace('/', '.', $request->getPathInfo())];
        Log::topic('request')::warning('request', array_merge($data, $params));
    }

    static public function start($path = null)
    {
        Time::start($path);
    }

    static public function end($path = null)
    {
        return Time::end($path);
    }

}

