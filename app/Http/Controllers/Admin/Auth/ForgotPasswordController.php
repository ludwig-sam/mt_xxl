<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends BaseController
{

    use SendsPasswordResetEmails;

    public function rule()
    {

    }

    public function __construct()
    {
        parent::notNeedToken();

        parent::notNeedPermission();

        parent::__construct();
        $this->middleware('guest');
    }
}
