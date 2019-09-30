<?php

namespace App\Console\Commands;

use App\Jacques\Mailer;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\User;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class MailAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailAdmins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MailAdmins';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::get();
        $message = "Dear DTE user please note that the interval reports and outage notifications are now configurable per user. Please configure your users to receive the reports/notifications if neccecary";
        $subject = "Message from the DTE developer(s)!!";
        foreach ($users as $user){
            if($user->user_type =="admin"){
                Mailer::sendMail($message,$subject,$user);
            }
        }

        // Device::pingall();
    }
}
