<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\ExeController;

class Exe implements RuleInterface {
    public function rule(){
        return [
            ExeController::class => [
                'create' => [
                    'store_id' => 'required',
                ],
                'update' => [
                    'id' => 'required',
                    'status' => 'required'
                ]
            ]
        ];
    }
}