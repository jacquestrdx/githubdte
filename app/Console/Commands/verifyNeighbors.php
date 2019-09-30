<?php

namespace App\Console\Commands;

use App\Neighbor;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class verifyNeighbors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifyNeighbors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verifyNeighbors';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Neighbor::verifyNeighbors();
    }
}
