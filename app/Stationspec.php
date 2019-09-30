<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stationspec extends Model
{
    public function statable(){
        return $this->belongsTo('App\Statable');
    }
}
