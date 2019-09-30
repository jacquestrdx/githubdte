<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devicetype extends Model
{
  	protected $fillable = ['name','sub_type'];

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }
}
