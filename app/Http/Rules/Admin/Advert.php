<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\AdvertController;

class Advert implements RuleInterface {
    public  function rule(){
        return [
            AdvertController::class => [
                'create' => [
                    'pic' => 'required|string',
                    'advert_position_id' => 'required|integer',
                ],
	            'update' =>[
		            'pic' => 'required|string',
	            ]
            ]
        ];
    }
}