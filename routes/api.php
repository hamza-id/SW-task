<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => 'api',
], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login',    'AuthController@login');
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'AuthController@logout');
        Route::get('/user',    'AuthController@user');

        Route::apiResource('movies', 'MovieController')->except(['store']);
    });
});

Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found.'
    ], Response::HTTP_NOT_FOUND);
});
