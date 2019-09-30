<?php

namespace App\Console\Commands;

use App\Backhaul;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkBackhauls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBackhauls {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkBackhauls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Backhaul::updateBackhaulInterfaces($this->argument('worker'));
    }
}
