<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Apifree;
use App\Temperature;
class Device extends Model
{
    //
    public function apifree(){
      return $this->hasOne(Apifree::class);
    }

    public function temperatures(){
      return $this->hasMany(Temperature::class);
    }
}
