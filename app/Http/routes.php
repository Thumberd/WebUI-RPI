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
    Route::get('/route', 'PanelController@code');

    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile/mail', 'ProfileController@storeMail');
    Route::post('/profile/apifree', 'ProfileController@storeApifree');

    Route::get('/devices', 'DeviceController@index');
    Route::post('/device', 'DeviceController@store');
    Route::delete('/device/{device}', 'DeviceController@delete');

    Route::get('/alarms/scheduled', 'DeviceController@scheduledAlarms');
    Route::post('/alarms/scheduled/add', 'DeviceController@addScheduled');

    Route::post('token', function (Request $req) {
        if (Auth::once(['email' => $req->input('email'), 'password' => $req->input('password')])) {
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
Route::group(['prefix' => 'api/v1', 'middleware' => 'API'], function () {

    Route::post('wakeonlan', 'ApiController@wakeOnLan');

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

    Route::get('ping', function (Request $req) {
        return 'Pong';
    });
});

Route::group(['prefix' => 'api/v2', 'middleware' => 'API'], function () {
    //Wake On Lan
        //POST "id"-> device id to power on
        Route::post('wakeonlan', 'ApiController@wakeOnLan');

    //Temperature
        //GET "device"-> device id's requested temperature
        Route::get('temperature/{device}', 'ApiController@getTemperature');
        //GET all temperatures from all sensors available
        Route::get('temperatures', 'ApiController@getAllTemperatures');
        //POST "temperature"-> value of temperature
        Route::post('temperature', 'ApiController@postTemperature');

    //Alarms
        //GET "device"-> device id
        Route::get('alarm/{device}', 'ApiController@getAlarmByDeviceId');
        //POST "device"-> device id. Change the state of the alarm.
        Route::post('alarm/{device}', 'ApiController@postChangeAlarmState');
        //GET scheduled alarms
        Route::get('alarms/scheduled', 'ApiController@getScheduledAlarms');
        //POST add scheduled alarm
        Route::post('alarm/{device}/scheduled', 'ApiController@postAddScheduled');
        //DELETE the scheduled alarms
        Route::delete('alarm/scheduled/{id}', 'ApiController@deleteScheduled');
        //GET inform the system that a movement has been detected
        Route::get('alarm/{device}/movement', 'ApiController@getSendAlarm');


    //Humidity
        //GET "device"-> device id's requested humidity
        Route::get('humidity/{device}', 'ApiController@getHumidity');
        //GET all temperatures from all sensors available
        Route::get('humiditys', 'ApiController@getAllHumiditys');
        //POST "humidity"-> value of humidity sensor
        Route::post('humidity', 'ApiController@postHumidity');

    //Plant-Humiditys
        //GET "device"-> device id's requested plant humidity
        Route::get('plant/humidity/{device}', 'ApiController@getPlantHumidity');
        //GET all temperatures from all sensors available
        Route::get('plant/humidities', 'ApiController@getAllPlantHumiditys');
        //POST "humidity"-> value of humidity sensor
        Route::post('plant/humidity', 'ApiController@postPlantHumidity');

    //Devices
        //GET "device"->device id
        Route::get('device/{device}', 'ApiController@getDevice');
        //GET all devices
        Route::get('devices', 'ApiController@getDevices');
        //POST re-generate token for the device
        Route::post('device/gen_token', 'ApiController@postDeviceGenerateToken');

    //Events
        //GET
        Route::get('events', 'ApiController@getEvents');
        //POST "event"->event's id to be mark as read
        Route::post('event/{event}', 'ApiController@postEventRead');
        //POST, mark all events read for an user
        Route::post('events/read', 'ApiController@postAllEventsRead');

    //Api Free Key
        //GET
        Route::get('apifree', 'ApiController@getApiFree');

    //Garages
        //GET
        Route::get('garages', 'ApiController@getGarages');
	//POST a garage to get up
	Route::post('garage/up/{g}', 'ApiController@postGarageUp');
        //POST validation code
        Route::post('garage/up', 'ApiController@postValidationCode');
        //GET "g"->garage id
        Route::get('garage/{g}', 'ApiController@getGarage');
        //POST "g"-> garage id, "state"-> state of the garage (0->close, 1->open)
        Route::post('garage/{g}', 'ApiController@postGarageState');
        Route::get('ping', function (Request $req) {
            return 'Pong';
        });
});

