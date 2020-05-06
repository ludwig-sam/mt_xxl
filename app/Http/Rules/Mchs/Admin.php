<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\AdminController;

class Admin implements RuleInterface {
    public  function rule(){
        return [
            AdminController::class => [
                'add' => [
                    'user_name' => 'required|unique:admin|string|max:255',
                    'password'  => 'required|string|min:6|confirmed',
                    'headurl'   => 'required|string',
                    'is_super'  => 'in:0,1',
                    'mch_id'    => 'Integer'
                ],
                'update' => [
                    'password'  => 'string|min:6|confirmed',
                    'headurl'   => 'string',
                    'is_super'  => 'in:0,1',
                    'mch_id'    => 'Integer'
                ]
            ]
        ];
    }

}