<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voipserver extends Model
{
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
