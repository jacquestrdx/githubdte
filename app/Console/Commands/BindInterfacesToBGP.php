<?php

namespace App\Console\Commands;

use App\Deviceinterface;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class BindInterfacesToBGP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BindInterfacesToBGP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind interfaces to BGP Peers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Deviceinterface::bindToBGPPeer();

           // Device::pingall();
    }
}
