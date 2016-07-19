<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Device;
use App\Temperature;
use App\Alarm;
use App;
use App\Event;
use App\Apifree;
use App\Garage;
use App\Humidity;
use App\PHumidity;
use App\Scheduled;
use Celery;

class ApiController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('API');
    }

    public function wakeOnLan(Request $req)
    {
        $device = Device::findOrFail($req->id);
        if ($device->type == "3") {
            if (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $device->code)) {
                exec('awake ' . $device->code);
                return "Request success";
            }
        }
        abort(406, 'Error: Device not found.');
    }

    public function getTemperature(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $temperature = Temperature::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
            return json_encode($temperature);
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllTemperatures(Request $req){
        $devices = Device::all();
        echo '[';
        $first = false;
        foreach ($devices as $device){
            if ($device->type == '4'){
                $temperature = Temperature::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
                if ($first != false){
                    echo ',';
                }
                else {
                    $first = true;
                }
                echo $temperature;
            }
        }
        echo ']';
    }

    public function postTemperature(Request $req)
    {
        $temperature = $req->input('temperature');
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key') AND $device->type == '4') {
                $entry = new Temperature;
                $entry->device_id = $device->id;
                $entry->value = $temperature;
                $entry->save();
                return "Request success";
            }
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = Humidity::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllHumiditys(Request $req){
        $devices = Device::all();
        $devicesHumiditys = "";
        foreach ($devices as $device){
            if ($device->type == '4'){
                $humidity = Humidity::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
                $devicesHumiditys += json_encode($humidity);
            }
        }
        return json_encode($devicesHumiditys);
    }

    public function postHumidity(Request $req)
    {
        $hum = $req->input('humidity');
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key') AND $device->type == '4') {
                $entry = new Humidity;
                $entry->device_id = $device->id;
                $entry->value = $hum;
                $entry->save();
                return "Request success";
            }
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getPlantHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = PHumidity::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllPlantHumiditys(Request $req){
        $devices = Device::all();
        $devicesHumiditys = "";
        foreach ($devices as $device){
            if ($device->type == '4'){
                $humidity = PHumidity::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
                $devicesHumiditys += $humidity;
            }
        }
        return json_encode($devicesHumiditys);
    }

    public function postPlantHumidity(Request $req)
    {
        $phum = $req->input('plant_humidity');
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key') AND $device->type == '4') {
                $entry = new PHumidity;
                $entry->device_id = $device->id;
                $entry->value = $phum;
                $entry->save();
                return "Request success";
            }
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAlarmByDeviceId(Request $req, Device $device)
    {
        if($device->type != "2"){
            abort(406, "Error: Device is not an alarm.");
        }
        return json_encode($device->alarm);
    }

    public function postChangeAlarmState(Request $req, Device $device)
    {
        if($device->type != "2"){
            abort(406, "Error: Device is not an alarm.");
        }
        $state = $device->alarm->state;
        if ($state == '1') {
            $device->alarm->state = false;
            $device->push();
            return 'Request success';
        } else if ($state == '0') {
            $device->alarm->state = true;
            $device->push();
            return 'Request success';
        }
        abort(500, "Error");
    }

    public function getSendAlarm(Request $req){
        $c = new Celery('localhost', 'guest', 'guest', '/');
        $c->PostTask('worker.alarm_protocol');
    }

    public function getScheduledAlarms(Request $req){
        return json_encode(Scheduled::all());
    }

    public function postAddScheduled(Request $req, Device $device){
        $scheduled = new Scheduled;
        $scheduled->alarm_id = $device->alarmId;
        $scheduled->beginHour = $req->input('beginHour');
        $scheduled->beginminute = $req->input('beginMinute');
        $scheduled->endHour = $req->input('endHour');
        $scheduled->endMinute = $req->input('endMinute');
        $scheduled->save();
    }

    public function deleteScheduled(Request $req, Scheduled $id){
        $id->delete();
        return 'Request success';
    }
    public function getDevice(Request $req, Device $device)
    {
        return json_encode($device->makeHidden(['token_id', 'token_key'])->toArray());
    }


    public function postDeviceGenerateToken(Request $req)
    {
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key')){
                $device->token_id = bin2hex(openssl_random_pseudo_bytes(6));
                $device->token_key = bin2hex(openssl_random_pseudo_bytes(12));
                $device->save();
                return json_encode($device);
            }
        }
    }

    public function getEvents(Request $req)
    {
        if (!empty($req->header('Token-Id'))) {
            $user = App\User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $events = App\Event::where('user_id', $user->id)->where('read', 0)->get();
                return json_encode($events);
            }
        }
        abort(401, "Only users can access Events.");
    }

    //Set an event as read
    public function postEventRead(Request $req, Event $event)
    {
        if (!empty($req->header('Token-Id'))) {
            $user = App\User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $event->read = true;
                $event->save();
                return "Request success";
            }
        }
        abort(401, "Only users can access Events.");

    }

    public function postAllEventsRead(Request $req){
        if (!empty($req->header('Token-Id'))) {
            $user = App\User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $events = Event::where('user_id', $user->id)->get();
                foreach ($events as $event){
                    $event->read = true;
                    $event->save();
                }
                return "Request success";
            }
        }
        abort(401, "Only users can access Events.");
    }

    public function getApiFree(Request $req)
    {
        return Apifree::all();
    }

    public function getGarages(Request $req)
    {
        return json_encode(Garage::all());
    }

    public function getGarage(Request $req, Garage $g)
    {
        return json_encode($g);
    }

    //UPDATE
    public function postGarageState(Request $req, Garage $g)
    {
        $g->state = $req->input('state');
        $g->save();
        return json_encode($g);
    }
}
