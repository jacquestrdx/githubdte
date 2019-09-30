<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DteConfigLoader extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        try{
            $databaseValue = \DB::select('select * from systems where id ="1"');
            $this->app['config']->set('mikrotik.api_username', $databaseValue['0']->mikrotikapi_user);
            $this->app['config']->set('mikrotik.api_password', $databaseValue['0']->mikrotikapi_password);
            $this->app['config']->set('ubnt.backupssh_password', $databaseValue['0']->backupssh_password);
            $this->app['config']->set('ubnt.backupssh_user', $databaseValue['0']->backupssh_user);
            $this->app['config']->set('ubnt.ubntssh_user', $databaseValue['0']->ubntssh_user);
            $this->app['config']->set('ubnt.ubntssh_password', $databaseValue['0']->ubntssh_password);
            $this->app['config']->set('ubnt.ubnt_snmpcommunity', $databaseValue['0']->ubnt_snmpcommunity);
            $this->app['config']->set('map.longitude', $databaseValue['0']->longitude);
            $this->app['config']->set('map.latitude', $databaseValue['0']->latitude);
            $this->app['config']->set('smtp.ip', $databaseValue['0']->smtp_ip);
            $this->app['config']->set('smtp.system_email_address', $databaseValue['0']->system_email_address);
            $this->app['config']->set('smtp.smtp_use_auth', $databaseValue['0']->smtp_use_auth);
            $this->app['config']->set('smtp.port', $databaseValue['0']->port);
            $this->app['config']->set('smtp.smtp_username', $databaseValue['0']->smtp_username);
            $this->app['config']->set('smtp.smtp_password', $databaseValue['0']->smtp_password);
            $this->app['config']->set('email.email_report_interval', $databaseValue['0']->HourlyReportInterval);
            $this->app['config']->set('dashboard.pppoe', $databaseValue['0']->include_hotspot);
            $this->app['config']->set('datatill_ip', $databaseValue['0']->datatill_ip);
            $this->app['config']->set('datatill_mysql_user', $databaseValue['0']->datatill_mysql_user);
            $this->app['config']->set('datatill_mysql_password', $databaseValue['0']->datatill_mysql_password);
            $this->app['config']->set('customer_router_password', $databaseValue['0']->customer_router_password);
            $this->app['config']->set('customer_router_username', $databaseValue['0']->customer_router_username);
        }catch (\Exception $e){

        }
    }

//

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
