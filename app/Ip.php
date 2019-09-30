<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IP extends Model
{
    protected $table = 'ips';

    public function device()
    {
        return $this->belongsTo('App\Device');
    }
}
