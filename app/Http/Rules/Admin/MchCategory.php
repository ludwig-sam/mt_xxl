<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\MchCategoryController;

class MchCategory implements RuleInterface {
    public  function rule(){
        return [
            MchCategoryController::class => [
                'add' => [
                    'name' => 'required|string|max:40',
                    //'pic' => 'required|string|max:200',
                    'is_use' => 'required|integer|max:1',
                ],
                'update' => [
                    'name' => 'required|string|max:40',
                    //'pic' => 'required|string|max:200',
                    'is_use' => 'required|integer|max:1',
                ],
                'chStatus' => [
                    'is_use' => 'required|boolean'
                ]
            ]
        ];
    }
}