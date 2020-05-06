<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\TagsController;

class Tags implements RuleInterface {
    public  function rule(){
        return [
            TagsController::class => [
                'add' => [
                    'name' => 'required|string|max:50',
                ],
                'update' => [
                    'name' => 'required|string|max:50',
                ]
            ]
        ];
    }
}