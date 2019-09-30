<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\DashHistory;

class addHistoricalEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addHistoricalEntry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an entry to the Dashboard db';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DashHistory::addEntry();
    }
}
