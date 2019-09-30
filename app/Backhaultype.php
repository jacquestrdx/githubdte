<?php

namespace App;

use App\Location;
use Illuminate\Database\Eloquent\Model;

class Backhaultype extends Model
{
    //Test COMMENT
    protected $fillable = ['id','name','colour'];

    public function backhauls(){
        return $this->hasMany('App\Backhaul');
    }

}
