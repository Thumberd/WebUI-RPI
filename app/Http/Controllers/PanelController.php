<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apifree;
use App\Device;

class PanelController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
    }
    public function index(Request $request){
      $api = Apifree::all();
      $wakeOnLan = Device::where('type', 3)->get();
      $temperaturesDevices = Device::where('type', 4)->get();
      return view('panel.index', ['api' => $api, 'wakeOnLan' => $wakeOnLan, 'temperaturesDevices' => $temperaturesDevices]);
    }

    public function alarmState(Request $req){
      $myfile = fopen("/home/dev/alarm/AlarmState", "r") or die("Unable to open file!");
      $t = fread($myfile, filesize("/home/dev/alarm/AlarmState"));
      fclose($myfile);
      return($t);
    }

    public function alarmStateUp(Request $req){
      if ($req->state == "1" or $req->state == "0"){
        $myfile = fopen("/home/dev/alarm/AlarmState", "w") or die("Unable to open file!");
        fwrite($myfile, $req->state);
        fclose($myfile);
        return "Success";
      }
      return "Error: invalid data";
    }
}
