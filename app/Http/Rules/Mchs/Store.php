<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\StoreController;

class Store implements RuleInterface {
    public  function rule(){
        return [
	        StoreController::class => [
                'create' => [
                    'name' => 'required|string|max:60',
                    'address' => 'required',
                    'pic' => 'required',
                ],
                'update' => [
                    'id' => 'required',
                    'status' => 'required'
                ]
            ]
        ];
    }
}