<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Statable;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkStationsIntergity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkStationsIntergity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkStationsIntergity';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Statable::checkStationsIntergity();

           // Device::pingall();
    }
}
