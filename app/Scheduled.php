<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduled extends Model
{
    protected $table = 'scheduled';
    public $timestamps = false;

    public function alarm() {
	return $this->belongsTo('App\Alarm');
    }
}
