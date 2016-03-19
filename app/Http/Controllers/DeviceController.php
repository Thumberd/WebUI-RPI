<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Device;

class DeviceController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
    }

    public function index(Request $request){
      $devices = Device::all();
      return view('device.index', ['devices' => $devices]);
    }

    public function store (Request $request){
      $this->validate($request, [
        'name' => 'required|max:255',
        'type' => 'integer|required',
        'code' => 'required|max:255'
      ]);

      $device = new Device;
      $device->name = $request->name;
      $device->code = $request->code;
      $device->type = $request->type;
      $device->ip = $request->ip;
      $device->save();

      return redirect('/devices');
    }

    public function delete(Request $request, Device $device){
      $device->delete();
      return redirect('/devices');
    }

}
