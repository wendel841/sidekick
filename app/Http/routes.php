<?php

Route::get('/', [
    'as' => 'dashboard',
    'uses' => 'HomeController@getDashboard',
]);

Route::post('/', 'HomeController@postDashboard');