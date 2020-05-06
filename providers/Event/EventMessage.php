<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午11:37
 */

namespace Providers\Event;


use Providers\Event\MsgTranslator\MsgTranslaterArray;
use Providers\Event\MsgTranslator\MsgTranslaterObject;
use Providers\Event\MsgTranslator\MsgTranslaterXml;
use Providers\Hook\Contracts\HookMessageContract;

class EventMessage extends HookMessageContract
{

    private $container;

    public function __construct($message)
    {
        $new_message = toArray($message);

        if ($new_message) {
            $this->container = new MsgTranslaterArray($new_message);

            return;
        }

        switch (gettype($message)) {
            case 'string':
                $this->container = new MsgTranslaterXml($message);
                break;
            case 'array':
                $this->container = new MsgTranslaterArray($message);
                break;
            case 'object':
                $this->container = new MsgTranslaterObject($message);
                break;
            default:
                $this->container = new MsgTranslaterArray([]);
                break;
        }
    }

    public function get($attr, $def = null)
    {
        return $this->container->get($attr, $def);
    }

    public function set($attr, $value)
    {
        $this->container->set($attr, $value);
    }

    public function toArray()
    {
        return $this->container->toArray();
    }
}