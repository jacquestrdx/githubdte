<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class Update_all_snmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Update_all_snmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UpdateallthisShaait';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::update_all_snmp();
           // Device::pingall();
    }
}
