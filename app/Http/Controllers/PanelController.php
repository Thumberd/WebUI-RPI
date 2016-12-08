<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apifree;
use App\Device;
use App\Alarm;
use App\Garage;
use Carbon\Carbon;
use App\Data;

class PanelController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->begin_Xmas = Carbon::create(2016, 12, 10, 0, 0, 0);
        $this->second = Carbon::create(2016, 9, 5, 20, 26, 1);
    }

    private function isEvent(){
        $actualYear = Carbon::now()->year;
        while (true) {
            //Xmas Check
            $begin = Carbon::create($actualYear, 12, 10, 0, 0, 0);
            $end = Carbon::create($actualYear, 12, 27, 0, 0, 0);
            if(Carbon::now()->between($begin, $end)) return "Xmas";

            //NYear Check
            $begin = Carbon::create($actualYear, 12, 27, 0, 0, 0);
            $end = Carbon::create($actualYear + 1, 01, 2, 0, 0, 0);
            if(Carbon::now()->between($begin, $end)) return "NYear";

            return false;
        }
    }

    public function index(Request $request){
        $pHum = Data::whereBetween('created_at', array(Carbon::now()->subDay(), Carbon::now()))->where('data_type', 3)->get();
        $api = Apifree::all();
        $alarms = Alarm::all();
        $wakeOnLan = Device::where('type', 3)->get();
        $temperaturesDevices = Device::where('type', 4)->get();
        $garages = Garage::all();
        $color = "grey";
        switch($this->isEvent()){
            case "Xmas":
                $color = "red";
                break;
            case "NYear":
                $color = "purple";
                break;
        }
        return view('panel.index', ['api' => $api, 'wakeOnLan' => $wakeOnLan,
            'temperaturesDevices' => $temperaturesDevices, 'alarms' => $alarms,
            'garages' => $garages, 'pHum' => $pHum, 'color' => $color]);
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
}
