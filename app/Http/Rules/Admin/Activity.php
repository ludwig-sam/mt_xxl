<?php namespace App\Http\Rules\Admin;

use Abstracts\RuleInterface;
use App\Http\Controllers\Admin\ActivityController;

class Activity implements RuleInterface
{
    public function rule()
    {
        return [
            ActivityController::class => [
                'create' => [
                    'name' => 'required',
                    'pic' => 'required',
                    'sort' => 'required|integer',
//	                "start_at" => 'required|string',
//	                "end_at" => 'required|string',
                ],
                'update' => [
                    'pic' => 'required',
                    'sort' => 'required|integer',
//		            "start_at" => 'required|string',
//		            "end_at" => 'required|string',
                ]
            ]
        ];
    }
}