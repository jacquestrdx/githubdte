<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Location;

class Possible_backhaul extends Model
{
    protected $table = 'possible_backhauls';


    public function getLocationName($id){
        $location = Location::find($id);
        return $location->name;
    }

}
