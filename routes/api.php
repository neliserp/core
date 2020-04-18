<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api',
    'namespace' => 'Neliserp\Core\Http\Controllers',
    'middleware' => 'api',
], function() {
    Route::resource('permissions', 'PermissionController')->except(['create', 'edit']);
    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::resource('users', 'UserController')->except(['create', 'edit']);
});
