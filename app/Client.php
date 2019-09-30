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


class Client extends Model

{

    protected $fillable = ['name', 'username', 'is_enterprise' ,'location_id', 'devicetype_id', 'reseller', 'ip', 'comment','mainbackhaultype','backupbackhaultype', 'backupbackhaul', 'powermonip', 'bwstaff_id', 'subnet', 'hscontact_id'];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function devicetype()
    {
        return $this->belongsTo('App\Devicetype');
    }

    public static function resetdownstoday(){
        $clients = Client::get();
        foreach ($clients as $client){
            $client->downs_today = 0;
            $client->save();
        }
    }

    public static function graphAllClientPPPOES(){
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->graphClientPPPOES();
    }

    public static function syncWithDatatill(){
        $month = date("Y-m-01");
        $clients = Client::get();
        foreach($clients as $client){
            $client->username = preg_replace('/\s/','',$client->username);
            $client->ip = preg_replace('/\s/','',$client->ip);
            $client->save();
        }
        $query = "select usage_to_date,usage_limit,username from view_radius_user_usage where username in (";
        foreach($clients as $client){
            $query .= '"'.preg_replace('/\s/','',$client->username).'"'.",";
            $clientarray[preg_replace('/\s/','',$client->username)] = array(
                "usage_to_date" => "",
                "usage_limit" => "",
            );
        }
        $query = substr($query, 0, -1);
        $query .= ') and product_month = "'.$month.'"';

        $con=mysqli_connect(config('datatill_ip'),config('datatill_mysql_user'),config('datatill_mysql_password'),"datatill");

        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $result = mysqli_query($con,$query);
        while ( $row[]=mysqli_fetch_array($result,MYSQLI_ASSOC));

        mysqli_free_result($result);
        mysqli_close($con);
        foreach($row as $line){
            $newquery = "UPDATE clients ";
            $newquery .= 'SET clients.radius_usage = "';
            $newquery .= round(($line['usage_to_date']/1024/1024),2).'",';
            $newquery .= 'clients.radius_cap = "';
            $newquery .= round(($line['usage_limit']/1024/1024),2).'"';
            $newquery .= ' where clients.username = "'.$line['username'].'"';
            \DB::statement($newquery);
        }



    }

    public static function fixspaces(){
        $clients = Client::get();
        foreach ($clients as $client){
            $client->username =  preg_replace('/\s/','',$client->username);
            $client->ip =  preg_replace('/\s/','',$client->ip);
            $client->save();
            $con=mysqli_connect("10.0.0.113","dte","L@roch00&","datatill");
            $query = 'select ip_address,username from view_radius_users where username="'.$client->username.'"';
            $result = mysqli_query($con,$query);
            if($result){
            while ( $row[] =mysqli_fetch_array($result,MYSQLI_ASSOC));
            mysqli_free_result($result);
            mysqli_close($con);
            }
        }
        $filename = "import.txt";
        $text = "";
        foreach($row as $key => $line)
        {
            if (isset($line)){
                foreach($line as $key=> $value){
                    $text .= $key." : ".$value."\n";
                }
            }
        }
        $fh = fopen($filename, "w") or die("Could not open log file.");
        fwrite($fh, $text) or die("Could not write file!");
        fclose($fh);
    }

}
