<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('test', 'HomeController@test');

Route::get('upload/{who}', 'HomeController@viewUpload');
Route::post('upload/{who}', 'HomeController@upload');

Route::post('uploadtest', 'HomeController@uploadTest');