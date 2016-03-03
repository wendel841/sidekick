<?php

Route::get('/', [
    'as' => 'dashboard',
    'uses' => 'HomeController@getDashboard',
]);
