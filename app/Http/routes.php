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

Route::get('/', ['as' => 'welcome', 'uses' => 'MainController@welcome']);

Route::get('gallery', ['as' => 'gallery', 'uses' => 'MainController@gallery']);

Route::get('map', ['as' => 'map', 'uses' => 'MainController@map']);

Route::get('maps/{source}', ['as' => 'map.tile', 'uses' => 'MainController@mapTile']);
