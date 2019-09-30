<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nmap extends Model
{
    protected $fillable = [
        "description","subnet","port_1","port_2","port_3","port_4","port_5"
    ];

    public function task()
    {
    }

    public static function ScanSubnet(){
        $nmaps = Nmap::get();
        foreach ($nmaps as $nmap){
            $results = exec('nmap -Pn -sUT 154.119.56.0/29 -p 8080,80,53,1723,22 --open',$output,$results);
            $finals = array ();
            $count = -1;
            echo "TEST\n";
            unset($output[0]);
            foreach($output as $line){
                echo $line."\n";
                try{
                    if(strpos($line,'Host')){
                        $finals[$count][] = $line;
                    }
                    if(strpos($line,'report')){
                        $count++;
                        $finals[$count][] = $line;
                    }
                    if( (strpos($line,'tcp')) or (strpos($line,'udp')) ){
                        $finals[$count][] = $line;
                    }
                }catch (\Exception $e){
                    echo "Exception \n".$e."\n";
                }

            }
            dd($finals);
        }
    }
}
