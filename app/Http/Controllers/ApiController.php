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

class ApiController extends Controller
{
    //
    public function __construct(){
      $this->middleware('API');
    }

    public function wakeOnLan(Request $req){
      $device = Device::findOrFail($req->id);
      if($device->type == "3"){
        if (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $device->code)){
          exec('awake ' . $device->code);
          return "Request executed";
        }
      }
      return "Error: no such Device";
    }

    public function temperature(Request $req){
      $device = Device::findOrFail($req->id);
      if ($device->type == '4'){
        $temperature = Temperature::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
        return $temperature;
      }
    }

    public function addTemperature(Request $req){
        $temperature = $req->input('temperature');
	if (!empty($req->header('Device-Id'))){
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key')) {
                $entry = new Temperature;
		$entry->device_id = $device->id;
		$entry->value = $temperature;
		$entry->save();
        }
      }
    }

    public function addHumidity(Request $req){
        $hum = $req->input('humidity');
        if (!empty($req->header('Device-Id'))){
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key')) {
                $entry = new Humidity;
                $entry->device_id = $device->id;
                $entry->value = $hum;
                $entry->save();
        }
      }
    }
	
    public function addPlantHumidity(Request $req){
        $phum = $req->input('plant_humidity');
        if (!empty($req->header('Device-Id'))){
            $device = App\Device::where('token_id', $req->header('Device-Id'))->first();
            if ($device AND $device->token_key == $req->header('Device-Key')) {
                $entry = new PHumidity;
                $entry->device_id = $device->id;
                $entry->value = $phum;
                $entry->save();
        }
      }
    }
    public function alarms(Request $req, Device $device){
      return $device->alarm;
    }

    public function alarm(Request $req, Device $device){
      $state = $device->alarm->state;
      if ($state == '1'){
        $device->alarm->state = false;
        $device->push();
        return 'Success';
      }
      else if ($state == '0'){
        $device->alarm->state = true;
        $device->push();
        return 'Success';
      }
      return "Error: invalid data";
    }
    //DEVICES API

    //Get all
    public function devices (Request $req){
      $devices = Device::all();
      return json_encode($devices);
    }
    public function device(Request $req, Device $device){
      return json_encode($device);
    }


    public function deviceGenerateToken(Request $req, Device $device){
	$device->token_id = bin2hex(openssl_random_pseudo_bytes(6));
	$device->token_key = bin2hex(openssl_random_pseudo_bytes(12));
	$device->save();
	return json_encode($device);
    }
    
    //Events Api
	//Get
	public function getEvent(Request $req){
		if (!empty($req->header('Token-Id'))) {
			$user = App\User::where('token_id', $req->header('Token-Id'))->first();
			if ($user AND $user->token_key == $req->header('Token-Key')) {
				$events = App\Event ::where('user_id', $user->id)->where('read', 0)->get();
				echo $events;
			}
		}
	}

	//Set an event as read
	public function eventRead(Request $req, Event $event) {
		$event->read = true;
		$event->save();
	}

    //Apifree API
	//GET
	public function apiFrees(Request $req){
		return Apifree::all();
	}

    //Garages API
	//GET
	public function garages(Request $req){
		return json_encode(Garage::all());
	}
	
	public function garage(Request $req, Garage $g){
		return json_encode($g);
	}
	//UPDATE
	public function garageup(Request $req, Garage $g){
		$g->state = $req->input('state');
		$g->save();
		return $g;
	}
}
