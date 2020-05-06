<?php

use Libs\Route;

Route::match('login', [Route::post], 'minipro.auth.login@login');

Route::match('register', [Route::post], 'minipro.auth.register@register');

Route::match('mini_login', [Route::post], 'minipro.auth.login@miniLogin');