<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\HistoricalPingWorker;


class StartHistoricalPingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartHistoricalPingJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartHistoricalPingJob';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        HistoricalPingWorker::GeneratePingFiles();
    }
}
