<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    // Prefixed with /auth
    'prefix' => 'auth',
], function() {
    Route::post('login', 'Api\AuthController@login');
    // Route::post('register', 'Api\AuthController@register');
    // Route::post('activate', 'Api\AuthController@activate');

    // // Requires Authorization
    Route::group([
        'middleware' => 'jwt.auth'
    ], function() {
        // Route::get('logout', 'Api\AuthController@logout');
        Route::post('sendData', 'Api\DataController@sendData');
        Route::get('getUser', 'Api\AuthController@getUser');
        Route::patch('password/change', 'AuthController@changePassword');
    });

    // // Limit number of requests per seconds, configured in app/Http/Kernel.php
    // Route::group([
    //     'middleware' => 'api',
    // ], function () {
    //     Route::post('password/token/create', 'Api\AuthController@createPasswordResetToken');
    //     Route::get('password/token/find/{token}', 'Api\AuthController@findPasswordResetToken');
    //     Route::patch('password/reset', 'Api\AuthController@resetPassword');
    // });
});
