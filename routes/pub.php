<?php

use Libs\Route;

Route::match('sms/verify', [Route::post], 'pub.sms@verify');
Route::match('sms/only_verify', [Route::post], 'pub.sms@onlyVerify');