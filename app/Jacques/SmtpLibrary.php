<?php

namespace App\Jacques;

class SmtpLibrary
{
    public function getQueueCount($ip){
        $connection = ssh2_connect($ip, 22);
        $command = "exim -bpc";
        ssh2_auth_password($connection, 'root','laroch007');
        $stream = ssh2_exec($connection, $command);
        stream_set_blocking($stream, true);
        while($line = fgets($stream)) {
            flush();
                $array[] = preg_replace("/\n/","",$line);
        }
        return $array['0'];
    }

    public function vangetQueue($ip){
        $connection = ssh2_connect($ip, 22);
        $command = "exim -bp | exiqsumm";
        ssh2_auth_password($connection, 'root','laroch007');
        $stream = ssh2_exec($connection, $command);
        stream_set_blocking($stream, true);
        while($line = fgets($stream)) {
            flush();
            $array[] = preg_replace("/\n/","",$line);
        }
        $headings = preg_split("/ +/",$array['1']);
        for ($x = 4; $x <= (sizeof($array)-4); $x++) {
            $tmpqueues = preg_split("/ +/",$array[$x]);
            $queues[] = array(
                "Count" => $tmpqueues['1'],
                "Volume" => $tmpqueues['2'],
                "Oldest" => $tmpqueues['3'],
                "Newest" => $tmpqueues['4'],
                "To-Domain" => $tmpqueues['5'],
            );
        }
        $data = array(
            "queues" => $queues,
            "headings" => $headings
        );

        return $data;
    }

    public function getCPU($ip){
        $connection = ssh2_connect($ip, 22);
        $command = 'top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk \'{print 100 - $1}\'';
        ssh2_auth_password($connection, 'root','laroch007');
        $stream = ssh2_exec($connection, $command);
        stream_set_blocking($stream, true);
        while($line = fgets($stream)) {
            flush();
            $array[] = preg_replace("/\n/","",$line);
        }
        return $array['0'];
    }

    public function getFreeMemory($ip){
        $connection = ssh2_connect($ip, 22);
        $command = 'cat /proc/meminfo | grep MemFree';
        ssh2_auth_password($connection, 'root','laroch007');
        $stream = ssh2_exec($connection, $command);
        stream_set_blocking($stream, true);
        while($line = fgets($stream)) {
            flush();
            $array[] = preg_replace("/\n/","",$line);
        }
        $result = preg_split("/MemFree: +/",$array['0']);
        return round((preg_replace("/kB/","",$result['1'])) / 1024);
    }

}
