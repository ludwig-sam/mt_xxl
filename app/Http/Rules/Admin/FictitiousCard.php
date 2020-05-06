<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\FictitiousCardController;

class FictitiousCard implements RuleInterface
{
    public function rule()
    {
        return [
            FictitiousCardController::class => [
                'create' => [
                    'card_name' => 'required|string|max:45',
                    'amount'    => 'required|numeric',
                    'stock'     => 'required|integer',
                ]
            ]
        ];
    }
}