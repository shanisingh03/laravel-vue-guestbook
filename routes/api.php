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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


Route::apiResources([
	'user' => 'API\UserController',
	'contact' => 'API\ContactController'
]);
Route::get('/finduser', 'API\ContactController@findUser');

Route::get('/dashboard', 'API\DashboardController@index');
Route::post('/profile/update', 'API\UserController@updateProfile');
Route::get('/myprofile', 'API\UserController@getMyProfile');
