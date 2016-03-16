<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apifree;
use App\Device;

class LocalController extends Controller
{
    //
    public function index(Request $request){
      $api = Apifree::all();
      $wakeOnLan = Device::where('type', 3)->get();
      $temperaturesDevices = Device::where('type', 4)->get();
      return view('local.index', ['api' => $api, 'wakeOnLan' => $wakeOnLan, 'temperaturesDevices' => $temperaturesDevices]);
    }

    public function info(Request $req){
      $handle = fopen("/home/dev/screen/ScreenInfo", "r");
      $info = array();
      $line = fgets($handle);
      array_push($info, $line);
      if (filesize("/home/dev/screen/ScreenInfo") > 0){
        $allLines = fread($handle,filesize("/home/dev/screen/ScreenInfo"));
        $newLines = $bodytag = str_replace($line, "", $allLines);
        file_put_contents("/home/dev/screen/ScreenInfo",$newLines);
      }
      fclose($handle);


      return json_encode($info);
      // $myfile = fopen("/home/dev/screen/ScreenInfo", "r") or die("Unable to open file!");
      // $t = fread($myfile, filesize("/home/dev/alarm/AlarmState"));
      // fclose($myfile);
    }
}
