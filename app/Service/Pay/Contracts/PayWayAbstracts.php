<?php namespace App\Service\Pay\Contracts;


use Illuminate\Support\Collection;

abstract class PayWayAbstracts{

    abstract public function param(Collection $collection);

    abstract public function way();
}