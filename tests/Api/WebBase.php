<?php namespace Tests\Api;

use App\Http\Codes\Code;
use Libs\Response;
use Libs\Route;
use Tests\Api\HelperLib\Curl;
use Tests\TestCase;

class   WebBase extends TestCase {

    const methods = [
        'get'           => 'get',
        'post'          => 'post',
        'postJson'      => 'postJson'
    ];

    private $_content;
    private $_response;
    private $_url;


    private function fillUrl($url){
        $host = 'http://mt.cn';
        $url = str_replace('http://localhost', $host, $url);
        return strpos($url, $host) === 0 ? $url : $host . $url;
    }


    private function setContent($content){
        $this->_content = $content;
    }

    public function getContent(){
        return $this->_content;
    }

    public function response(){
        return \Illuminate\Foundation\Testing\TestResponse::fromBaseResponse($this->_response);
    }

    public function parse($response){

        if(is_object($response)){
            $this->_response = $response;
            $content = $this->response()->getContent();

            $this->setContent($content);
        }else{
            $this->setContent($response);
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

    private function _call($method, $url, Array $data = [], Array $header = []){

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


