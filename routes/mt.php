<?php

use Libs\Route;

Route::match('access_token/get', [Route::post], 'receive.mtAccessToken@get');
