<?php namespace App\Http\Rules\Mchs;

use Abstracts\RuleInterface;
use App\Http\Controllers\Mchs\ExeOpratorController;

class ExeOprator implements RuleInterface {
    public  function rule(){
        return [
            ExeOpratorController::class => [
                'create' => [
                    'username' => 'required',
                    'mobile'   => 'required',
                    'store_id' => 'required',
                ],
                'update' => [
                    'username' => 'required',
                    'mobile' => 'required',
                ],
                'updateStatus' => [
                    'status' => 'in:DISABLED,NORMAL',
                ]
            ]
        ];
    }
}