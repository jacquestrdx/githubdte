<?php

namespace App\Jacques;

use App\Ip;
use App\BGPPeer;
use App\Ghost;
use App\Pppoeclient;
use App\Acknowledgement;
use App\Deviceinterface;
use App\HistoricalPingWorker;
use App\Jacques\InfluxLibrary;
use App\Jacques\MacVendorsApi;
use App\Statable;

class RuckusLibrary
{
    public static function getRuckusInfo(){
        try {
            echo "starting";
            $connection = ssh2_connect('10.250.35.36', 22);
            ssh2_auth_password($connection, "admin", "tfwisizwe00!");
            $stream = ssh2_exec($connection, 'admin');
            $stream = ssh2_exec($connection, 'tfwisizwe00!');
            $stream = ssh2_exec($connection, 'get boarddata');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                echo $line;
            }
        }  catch (\Exception $e) {

        }
    }

}