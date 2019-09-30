<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queuestats extends Model
{

    public static function PollGenericStats(){
        $sipserverip="192.168.65.176";

        $connection = ssh2_connect($sipserverip, 22);

        if (ssh2_auth_password($connection, 'root', 'BbW$@')) {
        } else {
        }

        $stream = ssh2_exec($connection,'asterisk -vvvvvrx "queue show"');
        stream_set_blocking($stream, true);
        $sshresult = stream_get_contents($stream);
        $results = preg_split('/\n/',$sshresult);
        foreach ($results as $result){
            if(preg_match('/strategy/',$result)){
                $result = preg_split('/calls/',$result);
                $temp = preg_split('/ has /',$result['0']);
                $array[] =  [$temp['0'],$temp['1']];
            }
        }

        return $array;
    }

    public static function PollExtentionStats(){
        $sipserverip="192.168.65.176";

        $connection = ssh2_connect($sipserverip, 22);

        if (ssh2_auth_password($connection, 'root', 'BbW$@')) {
        } else {
        }

        $stream = ssh2_exec($connection,'asterisk -vvvvvrx "queue show"');
        stream_set_blocking($stream, true);
        $sshresult = stream_get_contents($stream);
        $results = preg_split('/\n/',$sshresult);
        foreach ($results as $result){
            if(preg_match('/has taken/',$result)){
               $temp =  preg_split('/has taken/',$result);
               $extention = $temp[0];
               $extention = preg_split('/\s\(/',$extention);
               $extention = preg_replace('/\s/','',$extention[0]);
               $nocalls = $temp[1];
               $nocalls = preg_split('/calls/',$nocalls);
               $nocalls = preg_replace('/\s/','',$nocalls[0]);
               if ($nocalls=="no"){
                   $nocalls="0";
               }
               $array[] = [$extention,$nocalls];
            }
        }


        return $array;
    }


}
