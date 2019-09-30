<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downtime extends Model
{
    protected $fillable = [
        "comment","task_id"
    ];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

}
