<?php namespace App\Service\Wechat\Message;


use App\Exceptions\ReplyException;
use Libs\Log;
use App\Service\Wechat\Hook\Contracts\HookInterface;
use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HookMessage extends MessageAbsctracts {


    public function transform(Collection $material) : Message
    {
        $this->make($material)->hanlder($this->msgObj);

        return new Raw('');
    }

    private function make(Collection $material) : HookInterface
    {
        $name      = $material->get('name');

        $className = $this->spellingHookClassName($name);

        if(!class_exists($className)){
            throw new ReplyException("hook not exists:" . $name);
        }

        return new $className;
    }

    public function spellingHookClassName($name)
    {
        $hookNamespace = 'App\\Service\\Wechat';
        return $hookNamespace . '\\Hook\\' . Str::studly($name) . 'Hook';
    }

}

