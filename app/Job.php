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
use Pyrus\Logger;


class Job extends Model
{
    protected $fillable = [
        'id','date','location_id','start_km','end_km','technician','reg_nr','time_spent','km','fault_description','resolution','fiz_live','signal','pi_down','pi_up','mweb_down','mweb_up','created_at','updated_at','ccq','pi_latency','mweb_latency'
        ];


    public function report()
    {
        return $this->belongsTo('App\Tshwanereport');
    }
    public function stocks()
    {
        return $this->hasMany('App\Stock');
    }
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}