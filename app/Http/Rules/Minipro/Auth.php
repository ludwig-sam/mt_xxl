<?php namespace App\Http\Rules\Minipro;

use Abstracts\RuleInterface;
use App\Http\Controllers\Minipro\Auth\LoginController;

class Auth implements RuleInterface {
    public  function rule(){
        return [
            LoginController::class => [
                'miniLogin' => [
                    'iv' => 'required',
                    'code' => 'required',
                    'encrypt_data' => 'required'
                ]
            ]
        ];
    }
}