<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('systems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ubntssh_password');
			$table->string('ubntssh_user');
			$table->string('mikrotikapi_password');
			$table->string('mikrotikapi_user');
			$table->string('ubnt_snmpcommunity');
			$table->string('backupssh_password');
			$table->string('backupssh_user');
			$table->timestamps();
			$table->integer('enable_register')->default(0);
			$table->integer('enable_polling')->default(0);
			$table->string('longitude')->default('-25');
			$table->string('latitude')->default('28');
			$table->string('smtp_ip');
			$table->string('system_email_address')->default('0');
			$table->integer('smtp_use_auth')->default(0);
			$table->string('smtp_username')->default('0');
			$table->string('smtp_password')->default('0');
			$table->string('port')->default('25');
			$table->string('HourlyReportInterval')->default('0');
			$table->string('ftp_port_mikrotik')->default('0');
			$table->integer('include_hotspot')->default(0);
			$table->string('datatill_ip')->default('0');
			$table->string('datatill_mysql_user')->default('0');
			$table->string('datatill_mysql_password')->default('0');
			$table->string('iplist_secure_customer')->default('0');
			$table->string('customer_router_password')->default('0');
			$table->string('customer_router_username')->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('systems');
	}

}
