<?php
use Illuminate\Http\Request;
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
  Route::get('/timelapse', 'PanelController@timelapse');

  Route::get('/profile', 'ProfileController@index');
  Route::post('/profile/mail', 'ProfileController@storeMail');
  Route::post('/profile/apifree', 'ProfileController@storeApifree');

  Route::get('/devices', 'DeviceController@index');
  Route::post('/device', 'DeviceController@store');
  Route::delete('/device/{device}', 'DeviceController@delete');

  Route::get('/alarms/scheduled', 'DeviceController@ScheduledAlarms');
  Route::post('/alarms/scheduled/add', 'DeviceController@addScheduled');

  Route::post('token', function (Request $req){
    if (Auth::once(['email' => $req->input('email'), 'password' => $req->input('password')]))
      {
          Auth::user()->token_id = str_random(10);
          Auth::user()->token_key = str_random(60);
          Auth::user()->save();
          return json_encode(array('token_id' => Auth::user()->token_id, 'token_key' => Auth::user()->token_key));
      }
  });


  Route::get('/local', 'LocalController@index');
  Route::get('/localinfo', 'LocalController@info');
  //Route::auth();
  Route::get('/login', array('as' => 'login', 'uses' => 'Auth\AuthController@getLogin'));
  Route::post('/login', array('as' => 'login', 'uses' => 'Auth\AuthController@postLogin'));
  Route::get('/logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));
});

// Route::group(['middleware' => ['localhost']], function () {
//   Route::get('/local', 'LocalController@index');
//   Route::get('/localinfo', 'LocalController@info');
// });
Route::group(['prefix' => 'api/v1', 'middleware' => 'API'], function() {
      Route::post('wakeOnLan', 'ApiController@wakeOnLan');
      Route::post('temperature', 'ApiController@temperature');
      Route::post('alarms/{device}', 'ApiController@alarms');
      Route::post('alarm/up/{device}', 'ApiController@alarm');

      Route::post('temperature/add', 'ApiController@addTemperature');

      Route::post('humidity', 'ApiController@addHumidity');

      Route::post('plant-humidity', 'ApiController@addPlantHumidity');

      Route::get('devices', 'ApiController@devices');
      Route::get('device/{device}', 'ApiController@device');
      Route::post('device/gen_token/{device}', 'ApiController@deviceGenerateToken');

      Route::get('events', 'ApiController@getEvent');
      Route::post('event/{event}/read', 'ApiController@eventRead');

      Route::get('apifrees', 'ApiController@apifrees');

      Route::get('garages', 'ApiController@garages');
      Route::get('garage/{g}', 'ApiController@garage');
      Route::post('garage/{g}', 'ApiController@garageup');

      Route::get('ping', function(Request $req){
	return 'Pong';
	});
  });

