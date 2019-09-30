<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Sipaccount;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getSips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update software on scheduled devices';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Sipaccount::getsips();

           // Device::pingall();
    }
}
