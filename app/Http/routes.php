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

Route::get('certificates/preview', ['as' => 'certificates.preview', 'uses' => 'CertificatesController@preview']);
Route::get('certificates/pdf', ['as' => 'certificates.pdf', 'uses' => 'CertificatesController@pdf']);
Route::resource('certificates', 'CertificatesController');

Route::get('sketchboard', 'CertificatesController@sketchboard');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
