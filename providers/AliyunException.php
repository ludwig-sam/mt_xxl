<?php namespace Providers;


use Abstracts\ExceptionCaptureInterface;
use Aliyun\SLS\Client;
use Lokielse\LaravelSLS\SLSLog;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AliyunException implements ExceptionCaptureInterface {
    private $logger;
    private $options = [
        'access_key_id'     => '',
        'access_key_secret' => '',
        'endpoint'          => '',
        'project'           => '',
        'store'             => '',
        'topic'             => ''
    ];

    public function __construct($options)
    {
        $this->setOptions($options);

        $client = new Client($this->getEndPoint(), $this->getAccessKey(), $this->getAccessKeySecret());
        $log    = new SLSLog($client);

        $log->setProject($this->getProject());
        $log->setLogStore($this->getStore());

        $this->logger = $log;
    }

    private function setOptions($options)
    {
        foreach ($options as $name => $option){
            if(isset($this->options[$name]))$this->options[$name] = $option;
        }
    }

    public function captureException(\Exception $exception, $isError = false, $vars = null)
    {
        $origin = $this->formatException($exception);

        try{
            $this->logger->putLogs($origin, $this->getTopic(), request()->getHost(), date(DATE_ATOM));
        }catch (\Exception $exception){

            $log = new Logger('exception');
            $log->pushHandler(new StreamHandler(storage_path('logs/exception.log')));

            $log->error('异常写入失败aliyun', ['original' => $origin, 'new' => $this->formatException($exception)]);
        }
    }

    private function formatException(\Exception $exception){
        return  [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'msg'  => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
    }

    public function getAccessKey(){
        return $this->options['access_key_id'];
    }

    public function getAccessKeySecret(){
        return $this->options['access_key_secret'];
    }

    public function getProject(){
        return $this->options['project'];
    }

    public function getStore(){
        return $this->options['store'];
    }

    public function getEndPoint(){
        return $this->options['endpoint'];
    }

    public function getTopic(){
        return $this->options['topic'];
    }
}