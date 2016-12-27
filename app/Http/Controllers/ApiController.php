<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Device;
use App\Alarm;
use App;
use App\Event;
use App\Apifree;
use App\Garage;
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

    // Here is V2 of API
    // Now deprecated

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
        $response .= "'result': " . json_encode($result);
        //$response .= "'events': " . $events . '}';
	$response .= '}';
        return $response;

    }

    public function wakeOnLan(Request $req)
    {
        $device = Device::findOrFail($req->id);
        if ($device->type == "3") {
            if (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $device->code)) {
                exec('awake ' . $device->code);
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
            }
        }
        return response(json_encode(['status' => 'error', 'message' => 'The specified device is unable to be powered on remotely.']), 406)->header('Content-Type', 'application/json');
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
    return response(json_encode($result), 200)->header('Content-Type', 'application/json');
    }

    public function getTemperature(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
            return response(json_encode($temperature), 200)->header('Content-Type', 'application/json');
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
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
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
            }
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
    }

    public function getHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
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
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
            }
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
    }

    public function getPlantHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
            return json_encode($humidity);
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
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
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
            }
        }
        return response(json_encode(['status' => 'error', 'message' => 'Device unable to save that kind of data.'], 406))->header('Content-Type', 'application/json');
    }

    public function getAllAlarms(Request $req){
        return json_encode(Alarm::all());
    }

    public function getAlarmByDeviceId(Request $req, Device $device)
    {
        if($device->type != "2"){
            return response(json_encode(['status' => 'error', 'message' => 'The device specified is not an alarm.'], 406))->header('Content-Type', 'application/json');
        }
        return json_encode($device->alarm);
    }

    public function postChangeAlarmState(Request $req, Device $device)
    {
        if($device->type != "2"){
            return response(json_encode(['status' => 'error', 'message' => 'The device specified is not an alarm.'], 406))->header('Content-Type', 'application/json');
        }
        $state = $device->alarm->state;
        if ($state == '1') {
            $device->alarm->state = false;
            $device->push();
            return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
        } else if ($state == '0') {
            $device->alarm->state = true;
            $device->push();
            return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
        }
        abort(500, "Error");
    }

    public function getSendAlarm(Request $req, Device $device){
        $c = new Celery('localhost', 'guest', 'guest', '/');
        $c->PostTask('worker.alarm_protocol', array($device->id));
        return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
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
        return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
    }

    public function deleteScheduled(Request $req, Scheduled $id){
        $id->delete();
        return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
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
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
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
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
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
        if (strpos($ip, '192.168') !== false){
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.garage_authorized', array($g->id, $ip, $user->id));
            return response(json_encode(['status' => 'success', 'message' => 'Garage is opening']), 200)->header('Content-Type', 'application/json');
        }
        else {
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.send_code_garage', array($g->id, $ip, $user->id));
            return response(json_encode(['status' => 'success', 'message' => 'A validation code has been send']), 200)->header('Content-Type', 'application/json');
        }
    }

    public function postValidationCode(Request $req){
        $code = $req->input('code');
        $ip = $req->ip();
        $user = App\User::where('token_id', $req->header('Token-Id'))->first();
        if($user){
                $c = new Celery('localhost', 'guest', 'guest', '/');
                $c->PostTask('worker.send_validation_code', array($code, $ip, $user->id));
                return response(json_encode(['status' => 'success']), 200)->header('Content-Type', 'application/json');
        }
        return response(json_encode(['status' => 'error']), 200)->header('Content-Type', 'application/json');
    }

    // V3 API Service

    private function returnData($data){
        return json_encode(['status' => 'success', 'data' => $data]);
    }

    private function returnMessage($status, $userInfo, $details, $code){
        $response = response(json_encode(['status' => $status, 'user_info' => $userInfo, 'details' => $details]), $code)
            ->header('Content-Type', 'application/json');
        return $response;
    }

    private function returnFinal($data, $maxage, $lastmodified){
        $response = response($this->returnData($data), 200)
            ->header('Content-Type', 'application/json');
        if($maxage != 0) $response->header('Cache-Control', 'max-age=' . $maxage);
        if($lastmodified != null) {
		if(is_int($lastmodified)) {
			$response->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastmodified) . " GMT");
		} else {
			$response->header('Last-Modified', gmdate('D, d M Y H:i:s', strtotime($lastmodified)) . " GMT");
		}
	}
        return $response;
    }

    private function handleIfModifiedSinceHeader($lastmodified, $request){
        $ifmodifiedsince = $request->header('If-Modified-Since');
	if(!is_int($lastmodified)) $lastmodified = strtotime($lastmodified);
	if(strtotime($ifmodifiedsince) >= $lastmodified){
            return true;
        }
	return false;
    }

    public function V3getApp(Request $request){
        $alarms = Alarm::all();
        $garages = Garage::all();
        $devices = Device::where('type', '4')->get();
        $result = [];
        $dates = [];
        foreach ($devices as $device){
            $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')->first();
            $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
            $pHumidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
            if($temperature != null){
                array_push($result, $temperature);
                array_push($dates, $temperature['created_at']);
            }
            if($humidity != null){
                array_push($result, $humidity);
                array_push($dates, $humidity['created_at']);
            }
            if($pHumidity != null){
                array_push($result, $pHumidity);
                array_push($dates, $pHumidity['created_at']);
            }
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $request)) return response("Not Modified", 304);
        $response = ['alarms' => $alarms, 'garages' => $garages, 'result' => $result];
        return $this->returnFinal($response, '60', $max);

    }

    public function V3wakeOnLan(Request $req)
    {
        $device = Device::findOrFail($req->id);
        if ($device->type == "3") {
            if (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $device->code)) {
                exec('awake ' . $device->code);
                return $this->returnMessage('succcess', 'Le périphérique va s\'allumer', '', 200);
            }
        }
        return $this->returnMessage('fail', 'Le périhphérique ne peut être allumé à distance', 'The device cannot be powered remotely', 422);
    }

    public function V3getAllDatas(Request $req){
        $devices = Device::where('type', '4')->get();
        $result = [];
        $dates = [];
        foreach ($devices as $device){
                $temperature = Data::where('device_id', $device->id)->where('data_type', 1)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $humidity = Data::where('device_id', $device->id)->where('data_type', 2)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $pHumidity = Data::where('device_id', $device->id)->where('data_type', 3)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if($temperature != null){
                    array_push($result, $temperature);
                    array_push($dates, $temperature['created_at']);
                }
                if($humidity != null){
                    array_push($result, $humidity);
                    array_push($dates, $humidity['created_at']);
                }
                if($pHumidity != null){
                    array_push($result, $pHumidity);
                    array_push($dates, $pHumidity['created_at']);
                }
                $max = max(array_map('strtotime', $dates));
        }
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($result, '300', $max);

    }

    public function V3getTemperature(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $temperature = Data::where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')
                ->first();
            if($this->handleIfModifiedSinceHeader($temperature['created_at'], $req)) return response("Not Modified", 304);
            return $this->returnFinal($temperature, '300', $temperature['created_at']);
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des températures',
            'Device uncapable of saving temperature values', 422);
    }

    public function V3getAllTemperatures(Request $req){
        $devices = Device::where('type', '4')->get();
        $temperatures = [];
        $dates = [];
        foreach ($devices as $device){
            $temperature = Data::with('device')->where('device_id', $device->id)->where('data_type', 1)->orderBy('created_at', 'desc')
                ->first();
            array_push($temperatures, $temperature);
            array_push($dates, $temperature['created_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($temperatures, '300', $max);
    }

    public function V3postTemperature(Request $req)
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
                return $this->returnMessage('success', 'Température enregistrée', 'Saved !', 200);
            }
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des températures',
            'Device uncapable of saving tempeatures values', 422);
    }

    public function V3getHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')->first();
            if($this->handleIfModifiedSinceHeader($humidity['created_at'], $req)) return response("Not Modified", 304);
            return $this->returnFinal($humidity, '300', $humidity['created_at']);
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des humidités',
            'Device uncapable of saving humidity values', 422);
    }

    public function V3getAllHumiditys(Request $req){
        $devices = Device::where('type', 4)->get();
        $devicesHumiditys = [];
        $dates = [];
        foreach ($devices as $device){
            $humidity = Data::where('device_id', $device->id)->where('data_type', 2)->orderBy('created_at', 'desc')
                ->first();
            if($humidity != null) array_push($devicesHumiditys, $humidity);
            array_push($dates, $humidity['created_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($devicesHumiditys, '300', $max);
    }

    public function V3postHumidity(Request $req)
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
                return $this->returnMessage('success', 'Humidité enregistrée', 'Saved !', 200);
            }
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des humidités', 'Device uncapable of saving humidity values', 422);
    }

    public function V3getPlantHumidity(Request $req, Device $device)
    {
        if ($device->type == '4') {
            $humidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
            if($this->handleIfModifiedSinceHeader($humidity['created_at'], $req)) return response("Not Modified", 304);
            return $this->returnFinal($humidity, '300', $humidity['created_at']);
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des humidités', 'Device uncapable of saving humidity values', 422);

    }

    public function V3getAllPlantHumiditys(Request $req){
        $devices = Device::where('type', 4)->get();
        $plantHumiditys = [];
        $dates = [];
        foreach ($devices as $device){
            $humidity = Data::where('device_id', $device->id)->where('data_type', 3)->orderBy('created_at', 'desc')->first();
            if($humidity != null) array_push($plantHumiditys, $humidity);
            array_push($dates, $humidity['created_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($plantHumiditys, '300', $max);
    }

    public function V3postPlantHumidity(Request $req)
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
                return $this->returnMessage('success', 'Humidité enregistrée', 'Saved !', 200);
            }
        }
        return $this->returnMessage('fail', 'Le périphérique est incapable d\'enregistrer des humidités', 'Device uncapable of saving humidity values', 422);
    }

    public function V3getAllAlarms(Request $req){
        $alarms = Alarm::with('device')->get();
        $dates = [];
        foreach ($alarms as $alarm){
            array_push($dates, $alarm['updated_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($alarms, 60, $max);
    }

    public function V3getAlarmByDeviceId(Request $req, Device $device)
    {
        if($device->type != "2") return $this->returnMessage('fail', 'Le périhpérique spécifié n\'est pas une alarme',
            'Specified device isn\'t an alarm', 422);

        return $this->returnFinal($device->alarm, 60, $device->alarm->updated_at);
    }

    public function V3postChangeAlarmState(Request $req, Device $device)
    {
        if($device->type != "2") return $this->returnMessage('fail', 'Le périhpérique spécifié n\'est pas une alarme',
            'Specified device isn\'t an alarm', 422);
        $state = boolval($device->alarm->state);

        $device->alarm->state = !$state;
        $device->push();

        return $this->returnMessage('success', 'L\'alarme a été modifiée', !$state, 200);
    }

    public function V3postSendAlarm(Request $req, Device $device){
        $c = new Celery('localhost', 'guest', 'guest', '/');
        $c->PostTask('worker.alarm_protocol', array($device->id));
        return $this->returnMessage('success', 'L\'alarme s\'est déclenchée', 'Alarm protocol was initiated', 200);
    }

    public function V3getScheduledAlarms(Request $req){
        return $this->returnFinal(Scheduled::all(), 21600, null);
    }

    public function V3postAddScheduled(Request $req, Device $device){
        $scheduled = new Scheduled;
        $scheduled->alarm_id = $device->alarmId;
        $scheduled->beginHour = $req->input('beginHour');
        $scheduled->beginminute = $req->input('beginMinute');
        $scheduled->endHour = $req->input('endHour');
        $scheduled->endMinute = $req->input('endMinute');
        $scheduled->save();
        return $this->returnMessage('success', 'La programmation de l\'alarme a bien été enregistrée', 'Entry added in DB', 200);
    }

    public function V3deleteScheduled(Request $req, Scheduled $id){
        $id->delete();
        return $this->returnMessage('success', 'La programmation de l\'alarme a été supprimée.', 'Entry deleted', 200);
    }

    public function V3getDevice(Request $req, Device $device)
    {
        return $this->returnFinal($device->toArray(), 1200000, $device['updated_at']);
    }

    public function V3getDevices(Request $req){
        $dates = [];
        $devices = Device::all();
        foreach ($devices as $device){
            array_push($dates, $device['updated_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($devices->toArray(), 86000, $max);;
    }

    public function V3postDeviceGenerateToken(Request $req)
    {
        if (!empty($req->header('Device-Id'))) {
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key')){
                $device->token_id = bin2hex(openssl_random_pseudo_bytes(6));
                $device->token_key = bin2hex(openssl_random_pseudo_bytes(12));
                $device->save();
                return $this->returnFinal($device->makeVisible(['token_id', 'token_key'])->toArray(), 86000, $device['updated_at']);
            }
            return $this->returnMessage('fail', 'Les identifiants de connexion sont incorrects', 'Combination ID/KEY does not match our records', 403);
        }
        return $this->returnMessage('fail', 'Vous n\'avez pas précisé de tokens de connexion', 'Credentials not provided', 422);
    }

    public function V3getEvents(Request $req)
    {
        if (!empty($req->header('Token-Id'))) {
            $user = App\User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $events = Event::where('user_id', $user->id)->where('read', 0)->get();
                $lastEvent = Event::where('user_id', $user->id)->where('read', 0)->orderBy('created_at', 'desc')->first();
                if($this->handleIfModifiedSinceHeader($lastEvent['created_at'], $req)) return response("Not Modified", 304);
                return $this->returnFinal($events, 500, $lastEvent['created_at']);
            }
            return $this->returnMessage('fail', 'Les identifiants de connexion sont incorrects', 'Combination ID/KEY does not match our records', 403);
        }
        return $this->returnMessage('fail', 'Vous n\'avez pas précisé de tokens de connexion', 'Credentials not provided', 422);
    }

    //Set an event as read
    public function V3postEventRead(Request $req, Event $event)
    {
        if (!empty($req->header('Token-Id'))) {
            $user = User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $event->read = true;
                $event->save();
                return $this->returnMessage('success', 'L\'évènements a été marqué comme lu', 'Event read', 200);
            }
            return $this->returnMessage('fail', 'Les identifiants de connexion sont incorrects', 'Combination ID/KEY does not match our records', 403);
        }
        return $this->returnMessage('fail', 'Vous n\'avez pas précisé de tokens de connexion', 'Credentials not provided', 422);
    }

    public function V3postAllEventsRead(Request $req){
        if (!empty($req->header('Token-Id'))) {
            $user = User::where('token_id', $req->header('Token-Id'))->first();
            if ($user AND $user->token_key == $req->header('Token-Key')) {
                $events = Event::where('user_id', $user->id)->get();
                foreach ($events as $event){
                    $event->read = true;
                    $event->save();
                }
                return $this->returnMessage('success', 'Tous les évènements ont été marqués comme lus', 'Events read', 200);
            }
            return $this->returnMessage('fail', 'Les identifiants de connexion sont incorrects', 'Combination ID/KEY does not match our records', 403);
        }
        return $this->returnMessage('fail', 'Vous n\'avez pas précisé de tokens de connexion', 'Credentials not provided', 422);
    }

    public function V3getApiFree(Request $req)
    {
        $dates = [];
        $apifrees = Apifree::all();
        foreach ($apifrees as $apifree){
            array_push($dates, $apifree['updated_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($apifrees->toArray(), 86000, $max);;
    }

    public function V3getGarages(Request $req)
    {
        $dates = [];
        $garages = Garage::all();
        foreach ($garages as $garage){
            array_push($dates, $garage['updated_at']);
        }
        $max = max(array_map('strtotime', $dates));
        if($this->handleIfModifiedSinceHeader($max, $req)) return response("Not Modified", 304);
        return $this->returnFinal($garages->toArray(), 86000, $max);;
    }

    public function V3getGarage(Request $req, Garage $g)
    {
        return $this->returnFinal($g, 60, $g['updated_at']);
    }

    public function V3postGarageState(Request $req, Garage $g)
    {
        $g->state = $req->input('state');
        $g->save();
        return $this->returnFinal($g, 60, $g['updated_at']);
    }

    public function V3postOpenGarage(Request $req, Garage $g){
        $ip = $req->ip();
        $user = User::where('token_id', $req->header('Token-Id'))->first();
        if (strpos($ip, '192.168') !== false){
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.garage_authorized', array($g->id, $ip, $user->id));
            return $this->returnMessage('success', 'Le garage va s\'ouvrir', 'Garage is going to be opened', 200);
        }
        else {
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $c->PostTask('worker.send_code_garage', array($g->id, $ip, $user->id));
            return $this->returnMessage('pending', 'Un code de validation a été envoyé par SMS.', 'A validation cpde has been send.', 200);
        }
    }

    public function V3postValidationCode(Request $req){
        $code = $req->input('code');
        $ip = $req->ip();
        $user = User::where('token_iad', $req->header('Token-Id'))->first();
        if($user){
            $c = new Celery('localhost', 'guest', 'guest', '/');
            $result = $c->PostTask('worker.send_validation_code', array($code, $ip, $user->id));
            if($result == 200){
                return $this->returnMessage('success', 'Le garage va s\'ouvrir', 'Garage is going to be opened', 200);
            }
            return $this->returnMessage('fail', 'Le code est incorrect', 'Code is incorrect', 403);
        }
    }

    public function V3postPreferenceUpdate(Request $request, Preference $preference){
        $preference->value = $request->input('value');
        $preference->save();
        return $this->returnMessage('success', 'La préférence a été modifiée', 'Preference saved', 200);
    }

}
