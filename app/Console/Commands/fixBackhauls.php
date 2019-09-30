<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Backhaul;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class fixBackhauls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixBackhauls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix all backhauls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Backhaul::fixAllBackhauls();
    }
}
