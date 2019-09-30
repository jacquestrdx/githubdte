<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\HistoricalClientPingWorker;


class StartHistoricalClientPingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartHistoricalClientPingJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartHistoricalClientPingJob';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        HistoricalClientPingWorker::GeneratePingFiles();
    }
}
