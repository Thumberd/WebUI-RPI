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
use App\Data;

class ApiController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('API');
    }

    public function getApp(Request $request){
        $alarms = Alarm::all();
        $garages = Garage::all();
        $events = Event::all();
        $devices = Device::all();
        $result = [];
        foreach ($devices as $device){
            if($device->type == '4'){
                $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
                $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
                $pHumidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
                if($temperature != null){
                    array_push($result, $temperature);
                }
                if($humidity != null){
                    array_push($result, $humidity);
                }
                if($pHumidity != null){
                    array_push($result, $pHumidity);
                }
            }
        }
        $response = '{';
        $response .= "'alarms': " . $alarms . ',';
        $response .= "'garages': " . $garages . ',';
        $response .= "'result': " . $result . ',';
        $response .= "'events': " . $ $events . '}';$devices = Device::all();
        $result = [];
        foreach ($devices as $device){
            if($device->type == '4'){
                $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
                $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
                $pHumidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
                if($temperature != null){
                    array_push($result, $temperature);
                }
                if($humidity != null){
                    array_push($result, $humidity);
                }
                if($pHumidity != null){
                    array_push($result, $pHumidity);
                }
            }
        }
        return $result;
        return $response;

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

    public function getAllDatas(Request $req){
        $devices = Device::all();
        $result = [];
        foreach ($devices as $device){
            if($device->type == '4'){
                $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
                $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
                $pHumidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
                if($temperature != null){
                    array_push($result, $temperature);
                }
                if($humidity != null){
                    array_push($result, $humidity);
                }
                if($pHumidity != null){
                    array_push($result, $pHumidity);
                }
            }
        }
    return $result;
    }

    public function getTemperature(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
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
                $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
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
                $entry = new Data;
                $entry->data_type = 1;
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
            $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllHumiditys(Request $req){
        $devices = Device::all();
        $devicesHumiditys = "";
        foreach ($devices as $device){
            if ($device->type == '4'){
                $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
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
                $entry = new Data;
                $entry->data_type = 2;
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
            $humidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllPlantHumiditys(Request $req){
        $devices = Device::all();
        $devicesHumiditys = "";
        foreach ($devices as $device){
            if ($device->type == '4'){
                $humidity = PHumidity::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
                $devicesHumiditys += $humidity;
            }
        }
        return json_encode($devicesHumiditys);
    }

    public function postPlantHumidity(Request $req)
    {
        $phum = $req->input('plant_humidity');
        $phum = (($phum/1024) - 1) * 100 * (-1);
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key') AND $device->type == '4') {
                $entry = new Data;
                $entry->data_type = 3;
                $entry->device_id = $device->id;
                $entry->value = $phum;
                $entry->save();
                return "Request success";
            }
        }
        abort(406, 'Error: Device unable to save that type of data.');
    }

    public function getAllAlarms(Request $req){
        return json_encode(Alarm::all());
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

    public function getSendAlarm(Request $req, Device $device){
        $c = new Celery('localhost', 'guest', 'guest', '/');
        $c->PostTask('worker.alarm_protocol', array($device->id));
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
        return json_encode($device);
    }

    public function getDevices(Request $req){
        return json_encode(Device::all());
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

    public function postGarageState(Request $req, Garage $g)
    {
        $g->state = $req->input('state');
        $g->save();
        return json_encode($g);
    }

    public function postGarageUp(Request $req, Garage $g){
        $ip = $req->ip();
        $user = App\User::where('token_id', $req->header('Token-Id'))->first();
        $client = Device::where('ip', $ip);
	if (strpos($ip, '192.168') !== false){
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.garage_authorized', array($g->id, $ip, $user->id));
            return "Garage up";
        }
        else {
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.send_code_garage', array($g->id, $ip, $user->id));
            return "Validation code sent.";
        }
    }

    public function postValidationCode(Request $req){
        $code = $req->input('code');
        $ip = $req->ip();
        $user = App\User::where('token_id', $req->header('Token-Id'))->first();
        if($user){
                $c = new Celery('localhost', 'guest', 'guest', '/');
                $c->PostTask('worker.send_validation_code', array($code, $ip, $user->id));
        }
        return "Request sent.";
    }
}
