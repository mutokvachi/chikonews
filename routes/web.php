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

Route::get('/', ['uses'=>'LoginController@show', 'as'=>'login']);
Route::post('/', ['uses'=>'LoginController@store', 'as'=>'authorization']);

Route::get('auth/facebook', 'Auth\RegisterController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\RegisterController@handleProviderCallback');

Route::group(['middleware'=>'auth'], function(){
	Route::get('home',['uses'=>'LoginController@success', 'as'=>'home']);
	Route::post('home', ['uses'=>'LoginController@put', 'as'=>'add_product']);
	Route::get('/home/ajax', ['uses'=>'LoginController@ajax', 'as'=>'ajax']);
	Route::get('/logout', ['uses'=>'LoginController@logout']);

	Route::get('/scoreboard', ['uses'=>'ScoreController@show', 'as'=>'scoreboard']);
	Route::get('/posts', ['uses'=>'PostsController@show']);
	Route::get('/posts/delete/{id}', ['uses' =>'PostsController@destroy', 'as'=>'delete_post']);
	Route::post('/posts', ['uses'=>'PostsController@store', 'as'=>'posts']);
	Route::get('/posts/ajax', ['uses'=>'PostsController@ajax']);
	Route::post('/posts/ajax/messages', ['uses'=>'PostsController@messages', 'as'=>'postMessages']);
	Route::get('/posts/ajax/messages', ['uses'=>'PostsController@showMessages', 'as'=>'showMessages']);
	Route::get('/posts/delete/message/{id}', ['uses'=>'PostsController@destroyMessage']);

});


