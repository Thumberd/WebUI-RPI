<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apifree;
use App\Device;
use App\Alarm;
use App\Garage;

class PanelController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
    }
    public function index(Request $request){
      $api = Apifree::all();
      $alarms = Alarm::all();
      $wakeOnLan = Device::where('type', 3)->get();
      $temperaturesDevices = Device::where('type', 4)->get();
      $garages = Garage::all();
      return view('panel.index', ['api' => $api, 'wakeOnLan' => $wakeOnLan, 'temperaturesDevices' => $temperaturesDevices, 'alarms' => $alarms, 'garages' => $garages]);
    }

    public function timelapse(Request $req) {
	    return View('panel.timelapse', []);
    }

    public function code(Request $req){
        return View('panel.code', []);
    }
}
