<?php

use Illuminate\Http\Request;
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

/**
 * Route to allow user to login
 */
Route::post('/login', "UserController@login");

/**
 * Route to allow user to register 
 */
Route::post('/users', "UserController@store");

/**
 * Routes for user althenticated
 */
Route::group(['middleware' => ['auth:api']], function () {

	/**
	 * Routes for users
	 */
    Route::get('/users', "UserController@index");
	Route::get('/users/{user}', "UserController@show");
	Route::put('/users/{user}', "UserController@update");
	Route::patch('/users/{user}', "UserController@update");
	Route::delete('/users/{user}', "UserController@destroy");

	/**
	 * Routes for products
	 */
	Route::get('/products', 'ProductController@index');
	Route::post('/products', 'ProductController@store');
	Route::get('/products/{product}', 'ProductController@show');
	Route::put('/products/{product}', 'ProductController@update');
	Route::patch('/products/{product}', 'ProductController@update');
	Route::delete('/products/{product}', 'ProductController@destroy');
	
});