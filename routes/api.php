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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('check', 'AuthController@check');
	Route::post('refresh', 'AuthController@refresh');
});

Route::group(['middleware' => ['jwt.auth']], function () {
	Route::get('/user', function (Request $request) {
		return auth()->user();
	});

	Route::post('/test-user', function () {
		return auth()->user();
	});
});
