<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;

class Alarm extends Model
{
    //
    public function device(){
      return $this->belongsTo(Device::class);
    }
}
