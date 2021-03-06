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
    Route::get('/', 'PanelController@index');
    Route::get('/panel', 'PanelController@index');
    Route::get('/timelapse', 'PanelController@timelapse');
    Route::get('/code', 'PanelController@code');
    Route::get('/chart', 'PanelController@chart');
    Route::get('/preferences', 'PanelController@preferences');

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


    Route::post('gettoken', function (Request $req) {
        if (Auth::once(['email' => $req->input('email'), 'password' => $req->input('password')])) {
            return json_encode(array('token_id' => Auth::user()->token_id, 'token_key' => Auth::user()->token_key));
        }
    });

    //Route::auth();
    Route::get('/login', array('as' => 'login', 'uses' => 'Auth\AuthController@getLogin'));
    Route::post('/login', array('as' => 'login', 'uses' => 'Auth\AuthController@postLogin'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));
});


//Deprecated
Route::group(['prefix' => 'api/v2', 'middleware' => 'API'], function () {

    //Special response for app which give all real-time information
        Route::get('all', 'ApiController@getApp');
    //Wake On Lan
        //POST "id"-> device id to power on
        Route::post('wakeonlan', 'ApiController@wakeOnLan');

    //All infos datas
        //GET
        Route::get('datas', 'ApiController@getAllDatas');
    //Temperature
        //GET "device"-> device id's requested temperature
        Route::get('temperature/{device}', 'ApiController@getTemperature');
        //GET all temperatures from all sensors available
        Route::get('temperatures', 'ApiController@getAllTemperatures');
        //POST "temperature"-> value of temperature
        Route::post('temperature', 'ApiController@postTemperature');

    //Alarms
        //GET all alarms
        Route::get('alarms', 'ApiController@getAllAlarms');
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

Route::group(['prefix' => 'api/v3', 'middleware' => 'API'], function () {

    //Special response for app which give all real-time information
        Route::get('all', 'ApiController@V3getApp');
    //Wake On Lan
        //POST "id"-> device id to power on
        Route::post('wakeonlan', 'ApiController@V3wakeOnLan');

    //All infos datas
        //GET
        Route::get('datas', 'ApiController@V3getAllDatas');
    //Temperature
        //GET "device"-> device id's requested temperature
        Route::get('temperatures/{device}', 'ApiController@V3getTemperature');
        //GET all temperatures from all sensors available
        Route::get('temperatures', 'ApiController@V3getAllTemperatures');
        //POST "temperature"-> value of temperature
        Route::post('temperatures', 'ApiController@V3postTemperature');

    //Humidity
        //GET "device"-> device id's requested humidity
        Route::get('humiditys/{device}', 'ApiController@V3getHumidity');
        //GET all temperatures from all sensors available
        Route::get('humiditys', 'ApiController@V3getAllHumiditys');
        //POST "humidity"-> value of humidity sensor
        Route::post('humiditys', 'ApiController@V3postHumidity');

    //Plant-Humiditys
        //GET "device"-> device id's requested plant humidity
        Route::get('plants/humidity/{device}', 'ApiController@V3getPlantHumidity');
        //GET all temperatures from all sensors available
        Route::get('plants/humidity', 'ApiController@V3getAllPlantHumiditys');
        //POST "humidity"-> value of humidity sensor
        Route::post('plants/humidity', 'ApiController@V3postPlantHumidity');


    //Alarms
        //GET all alarms
        Route::get('alarms', 'ApiController@V3getAllAlarms');
        //GET "device"-> device id
        Route::get('alarms/{device}', 'ApiController@V3getAlarmByDeviceId');
        //POST "device"-> device id. Change the state of the alarm.
        Route::post('alarms/{device}', 'ApiController@V3postChangeAlarmState');
        //GET scheduled alarms
        Route::get('alarms/scheduled', 'ApiController@V3getScheduledAlarms');
        //POST add scheduled alarm
        Route::post('alarms/{device}/scheduled', 'ApiController@V3postAddScheduled');
        //DELETE the scheduled alarms
        Route::delete('alarms/scheduled/{id}', 'ApiController@V3deleteScheduled');
        //GET inform the system that a movement has been detected
        Route::post('alarms/{device}/movement', 'ApiController@V3postSendAlarm');


    //Devices
        //GET "device"->device id
        Route::get('devices/{device}', 'ApiController@V3getDevice');
        //GET all devices
        Route::get('devices', 'ApiController@V3getDevices');
        //POST re-generate token for the device
        Route::post('devices/gen_token', 'ApiController@V3postDeviceGenerateToken');

    //Events
        //GET
        Route::get('events', 'ApiController@V3getEvents');
        //POST "event"->event's id to be mark as read
        Route::post('events/{event}', 'ApiController@V3postEventRead');
        //POST, mark all events read for an user
        Route::post('events/read', 'ApiController@V3postAllEventsRead');

    //Api Free Key
        //GET
        Route::get('sms', 'ApiController@V3getApiFree');

    //Garages
        //GET
        Route::get('garages', 'ApiController@V3getGarages');
        //POST a garage to get up
        Route::post('garages/{g}/up', 'ApiController@V3postOpenGarage');
        //POST validation code
        Route::post('garages/up', 'ApiController@V3postValidationCode');
        //GET "g"->garage id
        Route::get('garages/{g}', 'ApiController@V3getGarage');
        //POST "g"-> garage id, "state"-> state of the garage (0->close, 1->open)
        Route::post('garages/{g}', 'ApiController@V3postGarageState');

    //Preference
        //POST "id" preferences id "value" new value
        Route::post('preferences/{preference}', 'ApiController@V3postPreferenceUpdate');
    Route::get('ping', function (Request $req) {
            return 'Pong';
        });
});

