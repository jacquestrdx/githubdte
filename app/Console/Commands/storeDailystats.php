<?php

namespace App\Console\Commands;

use App\Location;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class storeDailystats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storeDailystats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'storeDailystats';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Location::storeDailystats();
    }
}
