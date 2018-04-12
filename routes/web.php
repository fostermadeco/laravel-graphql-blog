<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WebController@index');

Route::get('/storage/images/{width}/{height}/{filename}', 'ImageTransformController@fit')
	->where(['width' => '[0-9]+', 'height' => '[0-9]+'])
	->name('image.transform.fit');

if(substr(request()->path() , 0, strlen('api/')) !== 'api/' && !in_array(request()->path(),['graphql-ui'])){
    Route::any('{slug?}','WebController@index')->where('slug', '([A-z\d-\/_.]+)?');
}