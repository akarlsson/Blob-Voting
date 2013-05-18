<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', function()
{
	if (Holmes::is_mobile()==false) {
		return Redirect :: to (Config::get('app.fbtablink').'&'.rand(0,3000000).'&ref=ts');
	} else {
		return View::make('hello');
	}
});

Route::any('/hello', function()
{
		return View::make('hello');

});


Route::any('/red', function()
{
	return Redirect :: to (Config::get('app.fbtablink').'&'.rand(0,3000000).'&ref=ts');
});

Route::any('/privacy', function()
{
	return Redirect :: to ('http://www.blob-europe.com/de/agb');
});

Route::any('/agb', function()
{
	return Redirect :: to ('http://www.blob-europe.com/de/agb');
});

Route::any('/start', function()
{
	return View::make('hello');
});

Route::resource('items', 'ItemsController');

Route::resource('users', 'UsersController');

Route::resource('votes', 'VotesController');