<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
	protected $fillable = ['name','created_at','updated_at'];


        public function sipextentions()
    {
        return $this->hasMany('App\Sipextention');
    }
}
