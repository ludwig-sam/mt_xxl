<?php namespace App\Http\Rules\Pay;

use Abstracts\RuleInterface;
use App\Http\Controllers\Pay\H5OrderController;


class H5PayRule implements RuleInterface
{

    public function rule()
    {
        return [
            H5OrderController::class => [
                'create' => [
                    'total_amount' => 'required|Numeric',
                    'exe_id'       => 'required',
                    'key'          => 'required'
                ],
                'cardList' => [
                    'key' => 'required'
                ],
                'calculation' => [
                    'exe_id'       => 'required',
                    'code_id'      => 'required|Integer'
                ],
                'memberInfo' => [
                    'exe_id'       => 'required',
                    'key'          => 'required'
                ],
                'consume' => [
                    'card_code'       => 'required',
                    'card_id'          => 'required'
                ]
            ]
        ];
    }

}