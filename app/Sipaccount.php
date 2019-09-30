<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jacques\NiceSSH;
use App\Sipserver;

class Sipaccount extends Model
{

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function acknowledgedlist()
    {
        return $this->hasMany('App\Sipaccount');
    }

    public function sipserver()
    {
        return $this->belongsTo('App\Sipserver');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }







//    public static function sshgetPeers()
//    {
//
//        if (!($stream = ssh2_exec($this->connection, 'asterisk -rx \'sip show peers\''))) {
//            throw new Exception('SSH command failed');
//        }
//        stream_set_blocking($stream, true);
//        $data = "";
//        while ($buf = fread($stream, 4096)) {
//            $data .= $buf;
//        }
//        fclose($stream);
//        return $data;
//    }
//
//    public static function sshgetPeer($peer)
//    {
//
//        if (!($stream = ssh2_exec($this->connection, 'asterisk -rx \'sip show peer '.$peer.'\''))) {
//            throw new Exception('SSH command failed');
//        }
//        stream_set_blocking($stream, true);
//        $data = "";
//        while ($buf = fread($stream, 4096)) {
//            $data .= $buf;
//        }
//        fclose($stream);
//        return $data;
//    }





    public static function getsips()
    {
        //\Log::info("--------------------------------------------------------------------------------------------");
        //\Log::info("GetSips Job started");
        //\Log::info("--------------------------------------------------------------------------------------------");

        $ssh = new NiceSSH();
        $sipservers=Sipserver::all();
        foreach ($sipservers as $sipserver)
        {
            $sipserverip = $sipserver->ip;

            echo "Starting getSips to update database on server".$sipserverip."\n";
            $ssh->connect($sipserver->ip);
            $peers = Sipaccount::getPeers($ssh);
           // $peers = $ssh->execssh('asterisk -rx \'sip show peers\'');
            $sipaccounts = Sipaccount::all();
            date_default_timezone_set('Africa/Johannesburg');
            foreach ($peers as $peer)
            {
                $isupdated = 0;
                foreach ($sipaccounts as $sipaccount) {

                    $peer = preg_replace('/\s+/', '', $peer);
                    $sipaccount->shortnumber = preg_replace('/\s+/', '', $sipaccount->shortnumber);
                    echo "matching ".$peer."-vs-".$sipaccount->shortnumber."\n";
                    if ($peer == $sipaccount->shortnumber)
                    {
                        $isupdated = 1;
                        $result1 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                        $result1 = preg_split('/\n/',$result1);
                        //$result1 = Sipaccount::sshgetPeer();
                        //exec("envoy run getpeer --peer=$peer",$result1);
                        foreach ($result1 as $row){
                            if (preg_match('/Useragent/',$row)){
                                $useragent = preg_split("/:/",$row);
                                $useragent = $useragent['1'];
                            }
                        }
                        $result2 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                        $result2 = preg_split('/\n/',$result2);

                        //$result2 = Sipaccount::sshgetPeer();
                        //exec("envoy run getpeer --peer=$peer",$result2);
                        foreach ($result2 as $row){
                            if (preg_match('/Addr->IP/',$row)){
                                $ipaddress = preg_split("/:/",$row);
                                $ipaddress = $ipaddress['1'];
                            }
                            if (preg_match('/Callerid/',$row)){
                                $longnumber = preg_split('/</',$row);
                                $username = preg_split('/\:/',$longnumber['0']);
                                if (array_key_exists('1',$username)){
                                    $username = preg_replace('/\"/','',$username['1']);
                                }else{
                                    $username = $peer;
                                }
                                $longnumber = preg_replace('/\>/','',$longnumber);
                                if (array_key_exists('1',$longnumber)){
                                    $longnumber = $longnumber['1'];
                                }else{
                                    $longnumber = $peer;
                                }
                            }
                        }
                        $result2 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                        $result2 = preg_split('/\n/',$result2);

                        //$result2 = Sipaccount::sshgetPeer();
                        //exec("envoy run getpeer --peer=$peer",$result2);
                        foreach ($result2 as $row){
                            if (preg_match('/Status/',$row)){
                                $status = preg_split("/:/",$row);
                                $status = $status['1'];
                                $status = preg_replace("/[^0-9]/", "", $status);
                            }
                        }

                        if($ipaddress!=" (null)")
                        {
                            $sipaccount->currentip = $ipaddress;
                            $sipaccount->status_id = 1;
                            $sipaccount->historicalip = $ipaddress;
                            $sipaccount->ping1 = $status;
                            $sipaccount->longnumber = $longnumber;
                            $sipaccount->username = $username;
                            $sipaccount->model = $useragent;
                            $sipaccount->lastonline = date("Y-m-d h:i:sa");
                            $sipaccount->lastupdate = date("Y-m-d h:i:sa");
    //                       Remove acknowledged sip from  acknowledged list
    //                        if($sipaccount->ack == 1) {
    //                            $sipaccount->ack = 0;
    //                            $Acknowledgedlists = Acknowledgedlist::where('sipaccount_id','=',$sipaccount->id)->first();
    //                            $Acknowledgedlists->delete();
    //                            echo "deleted Acknowledged list item");
    ////                       Save changes
    //                        }

                            $sipaccount->save();

                            echo "marked $sipaccount->username Online\n";
                            echo "updated $sipaccount->username\n";
                        }
                        else
                        {
                            $sipaccount->currentip = "(null)";
                            $sipaccount->status_id = 2;
                            $sipaccount->ping1 = 0;
                            $sipaccount->lastupdate = date("Y-m-d h:i:sa");

                            if($sipaccount->ack == 1)
                            {
                                $sipaccount->status_id = 3;
                                echo "Kept Acknowledged list item\n";
    //                       Save changes
                            }

                            $sipaccount->save();
                            echo "marked $sipaccount->username Offline\n";
                            echo  "updated $sipaccount->username \n";
                        }
                    }
                }
                if($isupdated==0){
                    echo "$peer is not found creating\n";

                    $result1 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                    $result1 = preg_split('/\n/',$result1);

                    //$result1 = Sipaccount::sshgetPeer();
                    //exec("envoy run getpeer --peer=$peer",$result1);
                    foreach ($result1 as $row){
                        if (preg_match('/Useragent/',$row)){
                            $useragent = preg_split("/:/",$row);
                            $useragent = $useragent['1'];
                        }
                    }
                    $result2 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                    $result2 = preg_split('/\n/',$result2);

                    //$result2 = Sipaccount::sshgetPeer();
                    //exec("envoy run getpeer --peer=$peer",$result2);
                    foreach ($result2 as $row){
                        if (preg_match('/Addr->IP/',$row)){
                            $ipaddress = preg_split("/:/",$row);
                            $ipaddress = $ipaddress['1'];
                        }
                    }
                    $result2 = $ssh->execssh('asterisk -rx \'sip show peer '.$peer.'\'');
                    $result2 = preg_split('/\n/',$result2);

                    //$result2 = Sipaccount::sshgetPeer();
                    //exec("envoy run getpeer --peer=$peer",$result2);
                    foreach ($result2 as $row){
                        if (preg_match('/Status/',$row)){
                            $status = preg_split("/:/",$row);
                            $status = $status['1'];
                            $status = preg_replace("/[^0-9]/", "", $status);
                        }
                        if (preg_match('/Callerid/',$row)){
                            $longnumber = preg_split('/</',$row);
                            $username = preg_split('/\:/',$longnumber['0']);
                            if (array_key_exists('1',$username)){
                                $username = preg_replace('/\"/','',$username['1']);
                            }else{
                                $username = $peer;
                            }
                            $longnumber = preg_replace('/\>/','',$longnumber);
                            if (array_key_exists('1',$longnumber)){
                                $longnumber = $longnumber['1'];
                            }else{
                                $longnumber = $peer;
                            }
                        }
                    }


                    $newsipaccount = new Sipaccount();
                    $newsipaccount->username = $username;
                    $newsipaccount->shortnumber = $peer;
                    $sipaccount->longnumber = $longnumber;
                    $newsipaccount->currentip = $ipaddress;
                    $newsipaccount->historicalip = $ipaddress;

                    if($ipaddress!=" (null)")
                    {
                        $newsipaccount->status_id = 1;
                        $sipaccount->lastonline = date("Y-m-d h:i:sa");
                        echo "marked $peer Online\n";
                    }
                    else
                    {
                        $newsipaccount->status_id = 2;
                        echo "marked $peer Offline\n";
                    }


                    $newsipaccount->ping1 = $status;
                    $newsipaccount->model = $useragent;
                    $newsipaccount->sipserver_id = $sipserver->id;
                    $newsipaccount->lastupdate = date("Y-m-d h:i:sa");
                    $newsipaccount->save();
                }
            }
           // $ssh->disconnect();
            echo "Ending getSips to update database for server".$sipserverip."\n";
        }
        echo "Ending getSips to update database from all servers\n";

        //\Log::info("--------------------------------------------------------------------------------------------");
        //\Log::info("GetSips Job ended");
        //\Log::info("--------------------------------------------------------------------------------------------");

    }

    public static function getPeers($ssh){
        $result = $ssh->execssh('asterisk -rx \'sip show peers\'');
        //exec('envoy run getallpeers',$result);
//        echo print_r($result));
        $result = preg_split('/\n/',$result);

        foreach($result as $row)
        {
            $str[] = explode(',', preg_replace("/\s+/", ",", $row));
        }

        foreach ($str as $peer)
        {
            $final[] = $peer['0'];
        }
        unset($final[count($final)-1]);
        unset($final['0']);
//        $kwagga[] = 0;
        foreach ($final as $row){
            $temp = preg_split("/\//", $row);
            $kwagga[] = $temp['0'];
        }
        return $kwagga;
    }

}



