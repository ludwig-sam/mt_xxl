<?php namespace App\Service\Pay\Contracts;

use Abstracts\Offsetable;

interface Dicscountable{

    function discountPrcie($totalAmount, Offsetable $offsetable);

    function canDis($totalAmount);
}