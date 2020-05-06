<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;

class Role implements RuleInterface {
    public  function rule(){
        return [
            RoleController::class => [
                'add' => [
                    'name' => 'required|string|max:60',
               ]
            ]
        ];
    }

}