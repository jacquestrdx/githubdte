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
use App\Neighbor;
use App\Jacques\CambiumLibrary;


class Voipclient extends Model

{

    protected $fillable = ['name', 'username', 'location_id', 'devicetype_id', 'reseller', 'ip', 'comment','mainbackhaultype','backupbackhaultype', 'backupbackhaul', 'powermonip', 'bwstaff_id', 'subnet', 'hscontact_id'];

    public static function getJitter(){

    }

}
