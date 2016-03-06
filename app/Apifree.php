<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Apifree extends Model
{
    //
    public function device(){
      return $this->belongsTo(User::class);
    }
}
