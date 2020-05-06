<?php

namespace App\Http\Controllers\Minipro\Auth;

use App\Http\Controllers\Minipro\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends BaseController
{


    use ResetsPasswords;

    protected $redirectTo = '/home';

    public function rule()
    {
        // TODO: Implement rule() method.
    }

    public function __construct()
    {
        parent::notNeedToken();

        parent::__construct();
        $this->middleware('guest');
    }

    public function registeRule()
    {
        // TODO: Implement registeRule() method.
    }
}
