<?php

namespace App\Jacques;


class NiceSSH
{
// SSH Host
//private $ssh_host = 'myserver.example.com';
// SSH Port
    private $ssh_port = 22;
// SSH Server Fingerprint
    //private $ssh_server_fp = 'konPN8BO1XWHB08bBcfEXZ/S80HgE27DhUcV3mpWD+I';
// SSH Username
    private $ssh_auth_user = 'root';
// SSH Public Key dte
    private $ssh_auth_pub = '/root/.ssh/id_rsa.pub';
// SSH Private Key File
    private $ssh_auth_priv = '/root/.ssh/id_rsa';
// SSH Private Key Passphrase (null == no passphrase)
    private $ssh_auth_pass;
// SSH Connection
    private $connection;

    public function connect($serverip)
    {

        if (!($this->connection = ssh2_connect($serverip, $this->ssh_port))) {
            throw new \Exception('Cannot connect to server');
        }
        $fingerprint = ssh2_fingerprint($this->connection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
//        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
//            throw new \Exception('Unable to verify server identity!');
//        }

        if (!ssh2_auth_pubkey_file($this->connection, $this->ssh_auth_user, $this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
            throw new \Exception('Autentication rejected by server');
        }
//        if (ssh2_auth_password($this->connection, 'root', $ssh_auth_pass)) {
//            echo "Authentication Successful!\n";
//        } else {
//            die('Authentication Failed...');
//        }

    }

    public function disconnect()
    {
        $this->execssh('echo "EXITING" && exit;');
        $this->connection = null;

    }

    public function execssh($cmd) {
        if (!($stream = ssh2_exec($this->connection, $cmd))) {
            throw new \Exception('SSH command failed');
        }

        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return $data;
    }

    public function __destruct() {
        $this->disconnect();
    }
}