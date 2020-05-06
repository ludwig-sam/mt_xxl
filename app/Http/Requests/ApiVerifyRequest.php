<?php namespace App\Http\Requests;

use App\Http\Rules;
use Libs\Route;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiVerifyRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        list($controller, $method, $fullController) = Route::action();

        try{
            return  Rules::rule()[$fullController][$method];
        }catch (\Exception $exception){
            return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        request()->headers->add([
            'Accept' => "application/json;"
        ]);

        parent::failedValidation($validator);
    }
}