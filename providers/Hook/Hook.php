<?php namespace Providers\Hook;


use Providers\Hook\Contracts\HookInterface;
use Providers\Hook\Contracts\HookMessageContract;
use Illuminate\Support\Str;

class Hook
{

    private $disptcher;

    public function __construct()
    {
        $this->disptcher = new HookDispatcher();
    }

    public function handle(Config $config, HookMessageContract $message)
    {
        $matcher = new Matcher();

        $matcher->isMatched($config, $message, $this);
    }

    public function callback(ConfigOption $option, HookMessageContract $message)
    {
        $this->disptcher->dispatch(self::make($option->name), $option, $message);
    }

    private function make($name):HookInterface
    {
        $className = self::spellingHookClassName($name);

        if (!class_exists($className)) {
            throw new \Exception("hook not exists:" . $name);
        }

        return new $className;
    }

    private function spellingHookClassName($name)
    {
        $hookNamespace = 'App\\Service\\Hooks\\';

        return $hookNamespace . Str::studly($name) . 'Hook';
    }

}

