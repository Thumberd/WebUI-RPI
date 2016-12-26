<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;

class Data extends Model
{
    protected $table = 'datas';

    public function device(){
      return $this->belongsTo(Device::class);
    }
}
