<?php namespace Tests\Api;

use App\Http\Codes\Code;
use Libs\Response;
use Libs\Route;
use Tests\Api\HelperLib\Curl;
use Tests\TestCase;

class   ApiBase extends TestCase {

    const methods = [
        'get'           => 'get',
        'post'          => 'post',
        'postJson'      => 'postJson'
    ];

    private $_token;
    private $_content;
    private $_response;
    private $_url;


    private function fillUrl($url){
        $host = 'http://mt.cn';
        $url = str_replace('http://localhost', $host, $url);
        return strpos($url, $host) === 0 ? $url : $host . $url;
    }

    protected function getToken(){
        return $this->_token;
    }

    private function setToken($token){
        if($token)$this->_token = $token;
    }

    private function setContent($content){
        $this->_content = is_string($content) ? json_decode($content)  : $content;

        if(json_last_error() != JSON_ERROR_NONE){

            $cacheFile  = storage_path('app') . '/response.cache.html';

            isDebug() && $this->saveResponseToFile($cacheFile, $content);

            $cacheFileUrl = 'http://mt.cn/response.cache.html';

            $this->error(sprintf("url is %s\nresponse is not a json\nopen [%s] with agent to see detail\n", $this->_url, $cacheFileUrl));
        }
    }

    private function saveResponseToFile($cacheFile, $content){
        file_put_contents($cacheFile, $content);
    }

    public function getContent($toString = false){
        return $toString ? json_encode($this->_content, JSON_UNESCAPED_UNICODE) : $this->_content;
    }

    public function response(){
        return \Illuminate\Foundation\Testing\TestResponse::fromBaseResponse($this->_response);
    }

    public function parse($response){

        if(is_object($response)){
            $this->_response = $response;
            $token   = $this->response()->headers->get(Response::token_name);
            $content = $this->response()->getContent();

            $this->setContent($content);
            $this->setToken($token);
        }else{
            $this->setContent($response);
            $this->setToken($this->getContentAttribute(Response::data_name)->{Response::token_name});
        }

    }

    public function isSuccess(){
        return $this->retcode() == Code::success;
    }

    public function retcode(){
        return $this->getContentAttribute(Response::retcode_name);
    }

    public function data(){
        return $this->getContentAttribute(Response::data_name);
    }

    private function getContentAttribute($name){
        return $this->_content->$name;
    }

    public function login(){
        $this->_url = $this->fillUrl(Route::named('login'));

        $response = Curl::post($this->_url, [
            'name'       => 'blue1',
            'password'   => '123456',
        ]);
        $this->parse($response);
    }

    private function _call($method, $url, Array $data = [], Array $header = []){

        $this->getToken() || $this->login();

        $header[Response::token_name] = 'bearer ' . $this->getToken();
        $header['X-Requested-With']   = 'XMLHttpRequest';

        $this->_url = $this->fillUrl($url);

        $response = $method == self::methods['get'] ? parent::get($this->_url, $header) : parent::$method($this->_url, $data, $header);

        $this->parse($response);

        return $response;
    }

    public function get($url, array $headers = []){
        return $this->_call(self::methods['get'], $url, [], $headers);
    }

    public function post($url, Array $data = [], Array $header = []){
        return $this->_call(self::methods['post'], $url, $data, $header);
    }

    public function false($condition, $message = '')
    {
        if($this->isSuccess()){
            $this->error($this->getContent(true));
        }
        parent::assertFalse($condition, $message);
    }

    public function true($condition, $message = ''){
        if(!$this->isSuccess()){
            $this->error($this->getContent(true));
        }
        parent::assertTrue($condition, $message);

    }


}


