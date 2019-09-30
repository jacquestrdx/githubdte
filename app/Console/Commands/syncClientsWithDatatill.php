<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Client;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class syncClientsWithDatatill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncClientsWithDatatill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'syncClientsWithDatatill';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Client::syncWithDatatill();
    }
}
