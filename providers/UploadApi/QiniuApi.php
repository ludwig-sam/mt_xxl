<?php namespace Providers\UploadApi;

use Abstracts\ApiResultInterface;
use Abstracts\UploaderInterface;
use Libs\Str;
use Providers\Api;
use Providers\QiniuApiResult;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuApi extends Api implements UploaderInterface {

    private $token;
    private $auth;
    protected $options = [
        'access_key' => '',
        'secret_key' => '',
        'bucket'     => ''
    ];

    public function newResult($ret)
    {
        return new QiniuApiResult($ret);
    }

    public function __construct($opations)
    {
        $this->setOptions($opations);
        $this->auth    = new Auth($this->getAccessKey(), $this->getSecretKey());
    }

    private function getToken()
    {
        if(is_null($this->token)){
            $this->token = $this->auth->uploadToken($this->getBucket());
        }
        return $this->token;
    }

    public function uploadFile($file)
    {
        $uploader = new UploadManager();
        $saveAs   = basename($file);

        list($ret, $err) = $uploader->putFile($this->getToken(), $saveAs, $file);

        $this->filePath($err, $ret);

        $result = $this->parseResult(['data' => $ret, 'err' => $err]);

        return $result->isSuccess();
    }

    private function uniqueName()
    {
        return time() . Str::rand(20) . '.jpg';
    }

    public function uploadString($string, $file_name = null)
    {
        $uploader = new UploadManager();

        $file_name = is_null($file_name) ? $this->uniqueName() : $file_name;

        list($ret, $err) = $uploader->put($this->getToken(), $file_name, $string);

        $this->filePath($err, $ret);

        $result = $this->parseResult(['data' => $ret, 'err' => $err]);
        return $result->isSuccess();
    }

    private function filePath($err, &$ret){
        if($err == null){
            $ret['path'] = 'http://' . config('qiniu.domain') . '/' . $ret['key'];
        }
    }

    public function getBucket(){
        return $this->options['bucket'];
    }

    public function getAccessKey()
    {
        return $this->options['access_key'];
    }

    public function getSecretKey()
    {
        return $this->options['secret_key'];
    }
}