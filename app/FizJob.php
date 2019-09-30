<?php

namespace App;


use App\Ip;
use App\Jacques\RuckusLibrary;
use App\Jacques\InfluxLibrary;
use App\Jacques\RouterosAPI;
use App\Jacques\UbntLibrary;
use App\Jacques\SiaeLibrary;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Pppoeclient;
use Khill\Lavacharts\Lavacharts;
use App\Deviceinterface;
use App\Jacques\MikrotikLibrary;
use App\Jacques\SmtpLibrary;
use App\Jacques\LigowaveLibrary;
use App\Jacques\DeviceNameFinder;
use Pyrus//\Logger;


class Job extends Model
{

    public function report()
    {
        return $this->belongsTo('App\Tshwanereport');
    }

}