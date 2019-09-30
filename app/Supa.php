<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supa extends Model
{
    protected $fillable = [
        "comment","task_id"
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

}
