<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
  Route::get('/', function () {
      return view('welcome');
  });
  Route::get('/panel', 'PanelController@index');
  Route::post('/alarmState', 'PanelController@alarmState');
  Route::post('/alarmStateUp', 'PanelController@alarmStateUp');

  Route::get('/profile', 'ProfileController@index');
  Route::post('/profile/mail', 'ProfileController@storeMail');
  Route::post('/profile/apifree', 'ProfileController@storeApifree');

  Route::get('/devices', 'DeviceController@index');
  Route::post('/device', 'DeviceController@store');
  Route::delete('/device/{device}', 'DeviceController@delete');

  Route::group(['prefix' => 'api/v1'], function () {
      Route::post('wakeOnLan', 'ApiController@wakeOnLan');
      Route::post('temperature', 'ApiController@temperature');
  });
  Route::auth();
});
