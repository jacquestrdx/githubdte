<?php

namespace App\Console\Commands;

use App\ClientPingWorker;
use App\Jacques\MicroInstrument;
use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getAllMikrotikVoltage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAllMikrotikVoltage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'getAllMikrotikVoltage';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            MikrotikLibrary::getMikotikVoltages();
        }catch(\Exception $e){

        }
        try{
            MicroInstrument::updateAllMicroInstruments();
        }catch(\Exception $e){

        }
    }
}
