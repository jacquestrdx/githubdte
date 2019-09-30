<?php

use Illuminate\Database\Seeder;

class SystemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('systems')->delete();
        
        \DB::table('systems')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ubntssh_password' => 'laroch007',
                'ubntssh_user' => 'admin',
                'mikrotikapi_password' => 'laroch007',
                'mikrotikapi_user' => 'monitor',
                'ubnt_snmpcommunity' => 'public',
                'backupssh_password' => 'admin',
                'backupssh_user' => 'laroch007',
                'created_at' => '2017-06-28 19:14:38',
                'updated_at' => '2019-08-01 08:14:55',
                'enable_register' => 0,
                'enable_polling' => 0,
                'longitude' => '28.273228',
                'latitude' => '-25.813919',
                'smtp_ip' => 'mail.bronbergwisp.co.za',
                'system_email_address' => 'dte@bronbergwisp.co.za',
                'smtp_use_auth' => 1,
                'smtp_username' => 'dte@bronbergwisp.co.za',
                'smtp_password' => 'M0th3rF#cker',
                'port' => '25',
                'HourlyReportInterval' => '1',
                'ftp_port_mikrotik' => '21',
                'include_hotspot' => 1,
                'datatill_ip' => '10.0.0.113',
                'datatill_mysql_user' => 'dte',
                'datatill_mysql_password' => 'L@roch00&',
                'iplist_secure_customer' => '0',
                'customer_router_password' => '0',
                'customer_router_username' => '0',
            ),
        ));
        
        
    }
}
