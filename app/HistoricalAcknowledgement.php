<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class HistoricalAcknowledgement extends Model
{

    protected $table = 'historical_acknowledgements';

}
