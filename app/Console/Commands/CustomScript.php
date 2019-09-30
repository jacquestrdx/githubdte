<?php

namespace App\Console\Commands;

use App\Ip;
use App\Jacques\RRDLibrary;
use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Jacques\RouterosAPI;
use App\Jacques\MikrotikLibrary;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class CustomScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CustomScript { worker }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CustomScript ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pppoeclients = Pppoeclient::where('type','!=','hotspot')->get();
        $count = ($pppoeclients->count()/20);
        $chunks = $pppoeclients->chunk($count);
        foreach ($chunks[$this->argument('worker')] as $pppoeclient)
        {
            echo "Logging into $pppoeclient->ip \n";
            $themikrotiklibrary = new MikrotikLibrary();
            if ($themikrotiklibrary->fix_hacked_router($pppoeclient->ip)){
                \Session::flash('status', 'Device successfully secured!');
                \Session::flash('notification_type', 'Success');
                //return view('hacked.success');
            }else{
                \Session::flash('status', 'Device failed to login!');
                \Session::flash('notification_type', 'Error');
                //return view('hacked.failure');
            }
        }

    }

}

