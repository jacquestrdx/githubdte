<?php

namespace App\Console\Commands;

use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class Hacker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hacker {job}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find Faulty devices';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        MikrotikLibrary::hacker($this->argument('job'));
    }
}
