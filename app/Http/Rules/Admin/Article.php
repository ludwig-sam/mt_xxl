<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\ArticleController;

class Article implements RuleInterface {
    public  function rule(){
        return [
            ArticleController::class => [
                'create' => [
                    'title' => 'required|string|max:50',
                ]
            ]
        ];
    }
}