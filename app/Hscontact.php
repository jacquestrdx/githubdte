<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hscontact extends Model
{
	protected $fillable = ['ip','name','surname','cellnum','cellnum2','email','address','created_at','updated_at'];


        public function location()
    {
        return $this->hasMany('App\Location');
    }
}
