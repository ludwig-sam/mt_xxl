<?php namespace Providers;

use Abstracts\Offsetable;
use Symfony\Component\HttpFoundation\Response;


class ResponseOffsetableAdapter implements Offsetable {

    private $response;
    public $content = [];
    private $data = [];

    function __construct(Response &$response)
    {
        $this->response = $response;
        $this->content  = json_decode($response->getContent(), true) ? : [];
        $this->data     = array_get($this->content, 'data', []);
    }

    public function offsetExists($name)
    {
        return isset($this->data[$name]);
    }

    public function offsetGet($name)
    {
        return array_get($this->data, $name);
    }

    public function offsetSet($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function save()
    {
        $this->content['data'] = $this->data;
        $this->response->setContent(json_encode($this->content, JSON_UNESCAPED_UNICODE));
    }

    public function all()
    {
        return $this->data;
    }


}