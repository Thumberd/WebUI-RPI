<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Apifree;
use App\Temperature;
use App\Alarm;

class Device extends Model
{
    //
    public function apifree(){
      return $this->hasOne(Apifree::class);
    }

    public function temperatures(){
      return $this->hasMany(Temperature::class);
    }

    public function alarm(){
      return $this->hasOne(Alarm::class);
    }
}
