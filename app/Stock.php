<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    protected $fillable = [ 'id','description','serial','highsiteform_id'];

    public function highsiteform()
    {
        return $this->belongsTo('App\Highsiteform');
    }

}

