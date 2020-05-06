<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\MchController;

class Mch implements RuleInterface {
    public  function rule(){
        return [
            MchController::class => [
                'add' => [
                    'name' => 'required|string|max:60',
                    'manager' => 'required|string|max:45',
                    'manager_phone' => 'required|numeric',
                    'logo' => 'required|string|max:300',
                    'mch_category_id' => 'required|integer',
                    'sort'            => 'numeric|max:999999'
	                //'banner' => 'required|array'
                ],
                'update' => [
                    'id'    => 'required',
                    'sort'  => 'numeric|max:99999'
                ]
            ]
        ];
    }
}