<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout');

Route::group(['middleware' => ['auth:sanctum', 'has_permission']], function() {
    Route::apiResource('permissions', 'PermissionController');
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('users', 'UserController');
});
