<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Highsiteform extends Model
{

    protected $fillable = [
        'ticket_nr',
        'user_ids',
        'location_id',
        'job_to_do',
        'job_done',
        'time_started',
        'highsite_visit_category_id',
        'time_ended',
        'notes'
    ];


    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function stock(){
        return $this->hasMany('App\Stock');
    }

    public function highsite_visit_category(){
        return $this->belongsTo('App\Highsite_visit_category');
    }
}
