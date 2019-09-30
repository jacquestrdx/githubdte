<?php

namespace App\Jacques;


class RRDLibrary
{
    public static function createRRD($data){
        $rrdFile = $data['rrdFile'];
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = $data['options'];
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        }
    }
    public static function updateRRD($data){
        $rrdFile = $data['rrdFile'];
        $time = time();
        //\Log::info("Updating RRD for $rrdFile at ".time());
        $updator = new \RRDUpdater($rrdFile);
        $updator->update($data['data'], $time);
    }

}