<?php

namespace App\Jacques;

/**
 * MacVendorsApi Class
 * @author http://macvendors.co
 * @version 1.0 this version tag is parsed
 */
//
class Mailer{
    public static function sendMail($message,$subject,$user){
        $mail = new \PHPMailer;
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();
        // Set mailer to use SMTP
        $mail->Host = config('smtp.ip');  // Specify main and backup SMTP servers
        if (config('smtp.smtp_use_auth')=="1"){
            $useauth = true;
        }else{
            $useauth = false;
        }
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPAuth = $useauth;                               // Enable SMTP authentication
        $mail->setFrom(config('smtp.system_email_address'), 'Mailer');
        $mail->Username = config('smtp.smtp_username');                 // SMTP username
        $mail->Password = config('smtp.smtp_password');
        $mail->Port = config('smtp.port');

        $mail->addAddress($user->email);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $message;
        if(!$mail->send()) {
            echo 'Message could not be sent.' ."\n";
            echo 'Mailer Error: ' . $mail->ErrorInfo."\n";
        } else {
            echo 'Message has been sent'."\n";
        }

    }
}
