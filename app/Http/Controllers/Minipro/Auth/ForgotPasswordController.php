<?php

namespace App\Http\Controllers\Minipro\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Minipro\BaseController;
use App\Http\Rules\Minipro\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends BaseController
{

    use SendsPasswordResetEmails;

    public function rule()
    {
        return new Auth();
    }


    public function __construct()
    {
        parent::notNeedToken();

        parent::__construct();

        $this->middleware('guest');
    }
}
