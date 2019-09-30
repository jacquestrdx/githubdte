<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOldDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('old_devices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('ip')->nullable()->unique('ip');
			$table->float('temp')->nullable()->default(0.00);
			$table->string('cpu')->nullable();
			$table->string('total_memory')->nullable();
			$table->string('free_memory')->nullable();
			$table->integer('active_pppoe')->nullable();
			$table->integer('maxactivepppoe')->nullable()->default(0);
			$table->integer('active_stations')->nullable();
			$table->integer('max_active_stations')->default(0);
			$table->integer('avg_ccq')->nullable();
			$table->string('volts')->nullable();
			$table->string('current')->nullable();
			$table->string('soft')->nullable();
			$table->string('firm')->nullable();
			$table->string('ping')->nullable()->default('0');
			$table->integer('downs_today')->nullable()->default(0);
			$table->integer('seconds_down_today')->default(0);
			$table->integer('playsound')->default(0);
			$table->string('location_id')->nullable();
			$table->string('devicetype_id')->nullable();
			$table->timestamps();
			$table->string('model')->nullable();
			$table->string('uptime')->nullable();
			$table->string('used_memory')->nullable();
			$table->string('lastseen')->nullable();
			$table->string('lastdown')->nullable()->default('0');
			$table->string('serial_no')->nullable();
			$table->integer('fault')->nullable();
			$table->string('dftqueuesze')->nullable();
			$table->text('neighbours')->nullable();
			$table->string('channel')->default('20');
			$table->string('freq')->nullable();
			$table->string('ssid')->nullable();
			$table->text('log')->nullable();
			$table->integer('poll')->nullable()->default(1);
			$table->string('lastsnmpupdate', 45)->nullable();
			$table->integer('pollstatus')->default(0);
			$table->integer('processing')->nullable()->default(0);
			$table->string('ping1', 45)->nullable()->default('0');
			$table->string('ping2', 45)->nullable()->default('0');
			$table->string('ping3', 45)->nullable()->default('0');
			$table->string('txpower', 64)->default('"n/a"');
			$table->string('wds', 64)->default('"n/a"');
			$table->string('airmaxq', 64)->default('"n/a"');
			$table->string('airmaxc', 64)->default('"n/a"');
			$table->text('serialno')->nullable();
			$table->integer('mikrotik_file_exists')->nullable()->default(0);
			$table->integer('sch_update')->default(0);
			$table->string('dns_server', 64)->nullable()->default('0');
			$table->text('fault_description')->nullable();
			$table->integer('backed_up')->nullable();
			$table->string('date_backed_up', 64)->nullable();
			$table->string('as_number', 64)->nullable();
			$table->integer('acknowledged')->nullable();
			$table->string('default_gateway')->nullable();
			$table->integer('default_gateway_id')->nullable();
			$table->string('txrate')->nullable();
			$table->string('rxrate')->nullable();
			$table->string('txsignal')->nullable();
			$table->string('rxsignal')->nullable();
			$table->integer('sstp_server')->nullable();
			$table->integer('pptp_server')->nullable();
			$table->integer('l2tp_server')->nullable();
			$table->integer('ovpn_server')->nullable();
			$table->integer('queue_count')->nullable();
			$table->string('ssh_username')->default('admin');
			$table->string('ssh_password')->default('laroch007');
			$table->string('wireless_mode', 64)->nullable();
			$table->string('backhaul_router', 45)->nullable();
			$table->string('mac_address')->nullable();
			$table->string('reseller', 64)->nullable();
			$table->string('client_datatill_link', 64)->nullable();
			$table->integer('max_active_hotspot')->default(0);
			$table->integer('active_hotspot')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('old_devices');
	}

}
