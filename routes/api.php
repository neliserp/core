<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api',
    'namespace' => 'Neliserp\Core\Http\Controllers',
], function() {
    Route::post('login', 'LoginController@login');

    Route::resource('permissions', 'PermissionController')->except(['create', 'edit']);
    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::resource('users', 'UserController')->except(['create', 'edit']);
});
