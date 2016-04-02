<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apifree;
use App\Device;
use App\Alarm;

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
      return view('panel.index', ['api' => $api, 'wakeOnLan' => $wakeOnLan, 'temperaturesDevices' => $temperaturesDevices, 'alarms' => $alarms]);
    }
}
