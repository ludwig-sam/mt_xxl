<?php

use Libs\Route;

Route::match('login', [Route::post], 'admin.auth.login@login')->name('login');

Route::match('register', [Route::post], 'admin.auth.register@register')->name('register');
