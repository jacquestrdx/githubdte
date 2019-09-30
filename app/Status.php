<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function sipaccounts()
    {
        return $this->hasMany('App\Sipaccount');
    }
}

