<?php

namespace App\Jacques;


class InfluxLibrary
{

    public static function writeToDB($database,$table,$data,$value){
        echo "Writing to Influx \n";
        $values = "";
        $time = time();
        foreach ($data as $key =>$lines){
            $values .= ",".trim($key)."=".trim($lines);
        }
        $command = "curl -i -XPOST 'http://localhost:8086/write?db=".$database."&precision=s' --data-binary '" .$table.
            $values." value=".$value.
            " $time" .
            "'";
        echo $command . "\n";
        exec($command);
    }

    public function selectFromDb($query){
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );

        $db = $client->dte;
        $stats = $db->query($query);

        return $stats;
    }

}