<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Highsite_visit_category extends Model
{
    public function Highsiteform(){
        return $this->hasMany('App\Highsiteform');
    }
}
