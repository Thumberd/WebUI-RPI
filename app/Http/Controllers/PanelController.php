<?php

namespace App\Http\Controllers;

use App\Preference;
use Illuminate\Http\Request;

use App\Device;
use Carbon\Carbon;
use App\Data;

class PanelController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
	$wakeOnLan = Device::where('type', 3)->get();
        return view('panel.index', ['wakeOnLan' => $wakeOnLan]);
    }

    public function timelapse(Request $req) {
	    return View('panel.timelapse', []);
    }

    public function code(Request $req){
        return View('panel.code', []);
    }

    public function chart(Request $req){
      if($req->input('by') == 'day'){
	$begin = Carbon::now()->subDay();
      } else if ($req->input('by') == 'week') {
	$begin = Carbon::now()->subWeek();
      } else if($req->input('by') == 'month') {
	$begin = Carbon::now()->subMonth();
      } else if($req->input('by') == 'year') {
	$begin = Carbon::now()->subYear();
      } else {
	$begin = Carbon::now()->subDay();
      }
      $tempDevices = Device::where('type', 4)->get();
      $temps = [];
      $hums = [];
      $pHum = [];
      foreach($tempDevices as $device){
	$data = Data::where('data_type', 1)->where('device_id', $device['id'])->whereBetween('created_at', array($begin, Carbon::now()))->get();
	if($data) {
	  $temps[$device['id']] = $data;
	}
      }

      foreach($tempDevices as $device){
        $data = Data::where('data_type', 2)->where('device_id', $device['id'])->whereBetween('created_at', array($begin, Carbon::now()))->get();
        if($data) {
          $hums[$device['id']] = $data;
        }
      }

      foreach($tempDevices as $device){
        $data = Data::where('data_type', 3)->where('device_id', $device['id'])->whereBetween('created_at', array($begin, Carbon::now()))->get();
        if($data) {
          $pHum[$device['id']] = $data;
        }
      }
      return view('panel.chart', ['tempDevices' => $tempDevices, 'temps' => $temps, 'hums' => $hums, 'pHum' => $pHum]);
    }

    public function preferences(Request $request){
        $preferences = Preference::all();
        return view('panel.preferences', ['preferences' => $preferences]);
    }
}
