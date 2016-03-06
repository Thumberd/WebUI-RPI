<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Device;
use App\Temperature;

class ApiController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
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
}
