<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use MongoDB\Driver\Command;
use App\Notification;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisaneign commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
            Commands\addHistoricalEntry::class,
            Commands\findPeakInterfaces::class,
            Commands\getAllMikrotikInterfaceNames::class,
            Commands\ResetFaults::class,
            Commands\PollIntracomWireless::class,
            Commands\CleanInflux::class,
            Commands\PollCiscos::class,
            Commands\ImportDevices::class,
            Commands\syncClientsWithDatatill::class,
            Commands\checkOneDeviceInterfaceThreshholds::class,
            Commands\StoreOneDInterfaces::class,
            Commands\getAllMikrotikIPs::class,
            Commands\ApiPollMikrotiks::class,
            Commands\getAllFizRoutes::class,
            Commands\Hacker::class,
            Commands\doClientSpeedTest::class,
            Commands\PollCustomOIDs::class,
            Commands\doSectorSpeedTest::class,
            Commands\fixBackhauls::class,
            Commands\StoreAllDInterfaces::class,
            Commands\createFizMonthlyReport::class,
            Commands\StartClientPingWorker::class,
            Commands\StartClientPingJob::class,
            Commands\checkInterfaceThreshholds::class,
            Commands\getAllSerialNumbers::class,
            Commands\FindDeviceLocationByDescription::class,
            Commands\PollSpesificDeviceType::class,
            Commands\PollSpesificDeviceByID::class,
            Commands\Update_all_snmp::class,
            Commands\sendDailyreport::class,
            Commands\MailAdmins::class,
            Commands\getAllMikrotikVoltage::class,
            Commands\sendHourlyReport::class,
            Commands\createMonthICMPSLAReport::class,
            Commands\checkAllPolling::class,
            Commands\resetPPPOECountMonthly::class,
            Commands\createWeekICMPSLAReport::class,
            Commands\createDayICMPSLAReport::class,
            Commands\create7daysICMPSLAReport::class,
            Commands\create30daysICMPSLAReport::class,
            Commands\create24hICMPSLAReport::class,
            Commands\checkStationsIntergity::class,
            Commands\getAllActivePPPOE::class,
            Commands\PollUBNTSectors::class,
            Commands\checkAllActivePPPOE::class,
            Commands\getAllPPPoeVendors::class,
            Commands\checkBackhauls::class,
            Commands\backupMikrotiks::class,
            Commands\StartPingWorker::class,
            Commands\StartPingJob::class,
            Commands\PollCambiumSectors::class,
            Commands\findPossibleBackhauls::class,
            Commands\verifyNeighbors::class,
            Commands\speedTestAllMikrotiks::class,
            Commands\StartHistoricalPingJob::class,
            Commands\StartHistoricalClientPingJob::class,
            Commands\StartHistoricalPingWorker::class,
            Commands\StartHistoricalClientPingWorker::class,
            Commands\ResetDownsToday::class,
            Commands\storeDailystats::class,
            Commands\graphAllClientPPPOES::class,
            Commands\getSips::class,
            Commands\Test::class,
            Commands\PollMikrotikSectors::class,
            Commands\getIPNeighbors::class,
            Commands\findFaults::class,
            Commands\checkPortLinks::class,
            Commands\GraphAllMikrotikInterfaces::class,
            Commands\graphOneMikrotikInterfaces::class,
            Commands\ScheduledSoftwareUpdate::class,
            Commands\checkBackups::class,
            Commands\getAllMikrotikPPPOECount::class,
            Commands\createFizWeeklyReport::class,
            Commands\getAllMikrotikRoutes::class,
            Commands\PollLigowaves::class,
            Commands\CustomScript::class,
            Commands\PollAirfibres::class,
            Commands\getLocationStatusCheck::class
    ];

    /**
     * Define the application's command schedule.
     *  getStatusCheck
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        echo "Starting Schedule Run \n";
        $schedule->call(function () {
            Notification::sendHourlyEmailToUsers(config('email.email_report_interval'));
        })->cron('0 */'.config('email.email_report_interval').' * * *');
        return;
    }

}
