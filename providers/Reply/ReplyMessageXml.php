<?php namespace Providers\Reply;


use Abstracts\ReplyMessageInterface;

class ReplyMessageXml extends ReplyMessageInterface {

    private $arr;

    public function __construct($xml)
    {
        $this->arr       = $this->xmlObjToArray(simplexml_load_string($xml));
    }

    private function xmlObjToArray($obj)
    {
        $result = [];
        foreach ($obj as $child){
            $result[$child->getName()] = (string)$child;
        }
        return $result;
    }

    public function getAttr($attr)
    {
        return array_get($this->arr, $attr);
    }

    public function setAttr($attr, $value)
    {
        array_set($this->arr,  $attr, $value);
    }

    public function toArray()
    {
        return $this->arr;
    }


}