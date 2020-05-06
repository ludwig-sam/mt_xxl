<?php namespace Providers;

use Abstracts\Offsetable;
use Illuminate\Http\Request;


class RequestOffsetableAdapter implements Offsetable {

    private $request;

    function __construct(Request &$request)
    {
        $this->request = $request;
    }


    public function offsetGet($name)
    {
        return $this->request->offsetGet($name);
    }

    public function offsetSet($name, $value)
    {
        $this->request->offsetSet($name, $value);
    }

    public function offsetExists($name)
    {
        return $this->request->offsetExists($name);
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function all()
    {
        return $this->request->all();
    }

}