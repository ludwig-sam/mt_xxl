<?php namespace App\Http\Rules;

use Abstracts\RuleInterface;
use App\Http\Controllers\Pub\UploadController;


class UploadRule implements RuleInterface {
    public  function rule(){
        return [
            UploadController::class => [
                'upload' => [
                    'file' => 'required'
                ]
            ]
        ];
    }
}