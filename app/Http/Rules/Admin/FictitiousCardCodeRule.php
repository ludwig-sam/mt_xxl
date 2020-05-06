<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\FictitiousCardCodeController;

class FictitiousCardCodeRule implements RuleInterface
{
    public function rule()
    {
        return [
            FictitiousCardCodeController::class => [
                'limit'  => [
                    'card_id' => 'required|integer',
                ],
                'export' => [
                    'card_id' => 'required|integer',
                ]
            ]
        ];
    }
}