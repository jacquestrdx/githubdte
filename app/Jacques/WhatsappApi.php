<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/09/05
 * Time: 12:49 PM
 */

namespace App\Jacques;

class WhatsappApi
{

    public function Register()
    {
        $username = "p 2748884138";
        $debug = true;

        // Create a instance of Registration class.
        $r = new \Registration($username, $debug);

        $r->codeRequest('sms'); // could be 'voice' too
        //$r->codeRequest('voice');
    }
}