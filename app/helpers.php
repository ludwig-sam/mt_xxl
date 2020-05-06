<?php

function isDebug()
{
    return config('app.debug') === true;
}

function isMiniProMudule()
{
    return config('auth.defaults.guard') == 'member';
}

function isAdminMudule()
{
    return config('auth.defaults.guard') == 'admin';
}

function isProd()
{
    return config('app.env') == 'prod';
}

function isDev()
{
    return config('app.env') == 'dev';
}

function isTest()
{
    return config('app.env') == 'test';
}

function systemEvent($message, $name = null)
{
    $message = new \Providers\Event\EventMessage($message);

    $event = new \Providers\Event\EventProvider\EventSystem($message);

    return $event->execute($name);
}

function wechatEvent($message)
{
    $message = new \Providers\Event\EventMessage($message);

    $event = new \Providers\Event\EventProvider\EventWechat($message);

    return $event->execute();
}

function toCollection($data)
{
    if ($data instanceof \Illuminate\Support\Collection) {
        return $data;
    }

    return new \Illuminate\Support\Collection(toArray($data));
}

function toArray($arrable)
{
    if (is_array($arrable)) return $arrable;

    if ($arrable instanceof \Illuminate\Contracts\Support\Arrayable) return $arrable->toArray();

    if ($arrable instanceof \Illuminate\Support\Collection) return $arrable->toArray();

    if ($arrable instanceof \Providers\Hook\Contracts\HookMessageContract) return $arrable->toArray();

    if ($arrable instanceof \Exception) {
        return [
            'code' => $arrable->getCode(),
            'msg'  => $arrable->getMessage(),
            'line' => $arrable->getLine(),
            'file' => $arrable->getFile(),
        ];
    }

    if (is_object($arrable)) {

        if (method_exists($arrable, 'toArray')) return $arrable->toArray();

        return get_class_vars($arrable);
    }

    return $arrable ? (array)$arrable : [];
}

function printLn($msg)
{

    if (defined('UNIT_TEST') && UNIT_TEST == true) {
        echo "\n";

        if (is_array($msg)) {
            json_encode($msg, JSON_UNESCAPED_UNICODE);
        } else {
            echo $msg;
        }
        echo "\n";
    }
}