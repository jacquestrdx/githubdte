<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $fillable = ['device_id', 'interface_id','type','value','level','active_time'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function interfaces(){
        return $this->hasManu('App\DInterface');
    }

}
