<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/3
 * Time: 下午6:27
 */

namespace Tests\Validator;


use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ValidateTest extends TestCase
{


    public function test_input()
    {
        $data = [
            "name" => "",
            "age"  => 20
        ];

        $rules = [
            "name" => "required",
            "age"  => "integer"
        ];

        $message = [
            "name.required" => "名称不能为空"
        ];

        $validator = \validator($data, $rules, $message);

        $validator->fails();

        $this->assertEquals("名称不能为空", $validator->getMessageBag()->first());
    }

    public function test_message()
    {
        $data = [
            "name" => ""
        ];

        $rules = [
            "name" => "required"
        ];

        $validator = \validator($data, $rules);

        $validator->fails();


        $this->assertEquals("The name field is required.", $validator->getMessageBag()->first());
    }

    public function test_exception()
    {
        $data = [
            "name" => ""
        ];

        $rules = [
            "name" => "required"
        ];

        $validator = \validator($data, $rules);

        $validator->fails();

        try{
            throw new \Illuminate\Validation\ValidationException($validator);

        }catch (\Illuminate\Validation\ValidationException $exception){

            $message_bag = new MessageBag($exception->errors());

            $this->assertEquals('The name field is required.', $message_bag->first());
        }
    }

}