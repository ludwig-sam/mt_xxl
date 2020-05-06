<?php namespace Tests\Providers;

use Abstracts\Offsetable;

class RequestMook implements Offsetable {
    private $data = [];


    public function save()
    {
    }

    public function offsetSet($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function offsetGet($name)
    {
        return array_get($this->data, $name);
    }

    public function offsetExists($name)
    {
        return isset($this->data[$name]);
    }

    public function all()
    {
        return $this->data;
    }


}