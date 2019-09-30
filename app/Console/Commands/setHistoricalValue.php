<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\DashHistory;
use App\Device;

class setHistoricalValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setHistoricalValue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'setHistoricalValue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::setHistoricalValue();
    }
}
