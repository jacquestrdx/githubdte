<?php

namespace App;

use App\Jacques\InfluxLibrary;
use Illuminate\Database\Eloquent\Model;

class Customsnmpoid extends Model
{

    protected $fillable = ['oid_to_poll','value_name','math','created_at','updated_at'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public static function pollAll(){
        $customoids = Customsnmpoid::where('poll','1')->get();
        foreach ($customoids as $customoid){
            $customoid->poll();
            $customoid->save();
        }
    }

    public function  poll(){
        try {
            $results = snmprealwalk($this->device->ip, $this->snmp_community, $this->oid_to_poll);
            if (is_array($results)){
                foreach ($results as $result){
                    $return_value = $result;
                    if (strpos($return_value,'STRING') !== false){
                        $final       = preg_split("/STRING: /", $return_value);
                    }
                    if (strpos($return_value,'INTEGER') !== false){
                        $final       = preg_split("/INTEGER: /", $this->oid_to_poll);
                    }
                    if(array_key_exists('1',$final)){
                        $this->return_value = $final['1']*$this->math;
                    }
                    $data = array();
                    $this->save();
                    InfluxLibrary::writeToDB('dte','customoids'.$this->id,$data,$this->return_value);
                }
            }
        }catch (\Exception $e){

        }
    }
}

