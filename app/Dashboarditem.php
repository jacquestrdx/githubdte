<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboarditem extends Model
{
    protected $fillable = ['title','name','surname','cellnum','cellnum2','email','address','created_at','updated_at'];

    public function items(){
        return $this->belongsTo('App\Dashboard');
    }
}
