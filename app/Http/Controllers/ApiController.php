<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Device;
use App\Temperature;
use App\Alarm;

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
}
