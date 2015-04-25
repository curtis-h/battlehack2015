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

Route::get('test', 'HomeController@tester');

Route::get('groupinfo', 'HomeController@groupinfo');
Route::post('groupinfo', 'HomeController@groupinfo');
Route::get('personinfo/{who}', 'HomeController@personinfo');


Route::get('makegroup', 'HomeController@makeGroup');
Route::get('makeperson/{who}', 'HomeController@makePerson');
Route::get('person/{who}', 'HomeController@person');
Route::get('train', 'HomeController@train');

Route::post('detect', 'HomeController@detect');
Route::get('detecttest', 'HomeController@detectTest');


Route::get('upload/{who}', 'HomeController@viewUpload');
Route::post('upload/{who}', 'HomeController@upload');
//Route::post('uploadtest', 'HomeController@uploadTest');
Route::post('uploadtest', 'HomeController@detect');
Route::any('detect64', 'HomeController@detect64');


