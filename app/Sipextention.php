<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jacques\NiceSSH;

class Sipextention extends Model
{
	protected $fillable = ['name','queue_id','created_at','updated_at'];


    public function queue()
    {
        return $this->belongsTo('App\Queue');
    }

    public static function getActive(){
        try{

        $sipserverip="192.168.65.176";
        $connection = ssh2_connect($sipserverip, 22);

        if (ssh2_auth_password($connection, 'root', 'BbW$@')) {
        } else {
        }
        $stream = ssh2_exec($connection,'asterisk -vvvvvrx "sip show inuse"');
        stream_set_blocking($stream, true);
        return $sshresult = stream_get_contents($stream);
        }catch (\Exception $e){
        }

    }

    public static function getRegistered(){
        $sipserverip="192.168.65.176";
        $connection = ssh2_connect($sipserverip, 22);
        if (ssh2_auth_password($connection, 'root', 'BbW$@')) {
        } else {
        }

        $stream = ssh2_exec($connection,'asterisk -vvvvvrx "sip show peers"');
        stream_set_blocking($stream, true);
        return $sshresult = stream_get_contents($stream);
    }

    public static function getActiveCalls(){
        try{

        $sipserverip="192.168.65.176";

        $connection = ssh2_connect($sipserverip, 22);

        if (ssh2_auth_password($connection, 'root', 'BbW$@')) {
        }
        else{
        }

        $stream = ssh2_exec($connection,"sudo asterisk -vvvvvrx 'core show channels' | grep 'active call'");
        stream_set_blocking($stream, true);
        $sshresult = stream_get_contents($stream);
        $str =strtok($sshresult, ' ');
        return $str;
        }catch (\Exception $e){
        }



    }

    public static function determinestatus($status){
        $array = explode('/',$status);
        if ($array[0]==$array[1]){
            if($array[0]>0){
                return '<span style="color:Orange">[Ringing Only]</span>';

            }

        }
        if ($array[0]!=$array[1]){
            if($array[0]==0){
                return '<span style="color:Green">[Ready]</span>';
            }
            if( ($array[0]>0) and ($array[1]==0)){
                return '<span style="color:Red">[On Call]</span>';
            }
            if( ($array[0]>0) and ($array[1]>0)){
                return '<span style="color:Red">[Ringing On Call]</span>';
            }

        }else{
            return '<span style="color:Green">[Ready]</span>';
        }
    }

}
