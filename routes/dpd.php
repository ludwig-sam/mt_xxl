<?php

use Libs\Route;

Route::match('gateway/set', [Route::post], 'dependence.gateway@set');
Route::match('gateway/get', [Route::get], 'dependence.gateway@get');