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

Route::group(['middleware' => 'secure.content'], function () {
    Route::get('/', ['as' => 'welcome', 'uses' => 'MainController@welcome']);

    Route::get('gallery', ['middleware' => 'secure.content:flickr', 'as' => 'gallery', 'uses' => 'MainController@gallery']);

    Route::get('map', ['middleware' => 'secure.content:google', 'as' => 'map', 'uses' => 'MainController@map']);
});

Route::get('maps/{source}', ['as' => 'map.tile', 'uses' => 'MainController@mapTile']);
