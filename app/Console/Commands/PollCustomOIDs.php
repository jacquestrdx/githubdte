<?php

namespace App\Console\Commands;

use App\Customsnmpoid;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class PollCustomOIDs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PollCustomOIDs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll all PollCustomOIDs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Customsnmpoid::pollAll();

           // Device::pingall();
    }
}
