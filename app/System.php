<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    //¸
	protected $fillable = [
	    'latitude',
        'include_hotspot',
        'longitude',
        'ubntssh_password',
        'ubntssh_user',
        'backupssh_password',
        'backupssh_user',
        'mikrotikapi_user',
        'smtp_use_auth',
        'smtp_username',
        'smtp_password',
        'system_email_address',
        'port',
        'mikrotikapi_password',
        'ubnt_snmpcommunity',
        'created_at',
        'smtp_ip',
        'updated_at',
        'customer_router_username',
        'customer_router_password',
        'datatill_ip',
        'datatill_mysql_user',
        'datatill_mysql_password',
        'HourlyReportInterval',
        'HourlyReportInterval',
        'ftp_port_mikrotik'
    ];

}
