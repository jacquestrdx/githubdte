<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
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
			$table->string('ping', 45)->nullable()->default('1');
			$table->integer('downs_today')->nullable()->default(0);
			$table->string('location_id')->nullable();
			$table->string('devicetype_id')->nullable();
			$table->timestamps();
			$table->string('model')->nullable();
			$table->string('uptime')->nullable()->default('600');
			$table->string('used_memory')->nullable();
			$table->string('lastseen')->nullable();
			$table->string('lastdown')->nullable()->default('0');
			$table->string('serial_no')->nullable();
			$table->string('dftqueuesze')->nullable();
			$table->string('channel')->default('20');
			$table->string('freq')->nullable();
			$table->string('ssid')->nullable();
			$table->text('log')->nullable();
			$table->string('lastsnmpupdate', 45)->nullable();
			$table->integer('pollstatus')->default(0);
			$table->string('ping1', 45)->nullable()->default('1');
			$table->string('ping2', 45)->nullable()->default('1');
			$table->string('ping3', 45)->nullable()->default('1');
			$table->string('txpower', 64)->default('"n/a"');
			$table->string('wds', 64)->default('"n/a"');
			$table->string('airmaxq', 64)->default('"n/a"');
			$table->string('airmaxc', 64)->default('"n/a"');
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
			$table->integer('max_active_hotspot')->default(0);
			$table->integer('active_hotspot')->default(0);
			$table->integer('voltage_monitor')->default(0);
			$table->integer('voltage_threshold')->default(0);
			$table->dateTime('voltage_seen_at')->nullable();
			$table->integer('ping4')->default(1);
			$table->string('comment');
			$table->integer('voltage_offset')->default(0);
			$table->string('solar_charge');
			$table->string('batt1');
			$table->string('batt2');
			$table->string('batt3');
			$table->string('batt4');
			$table->integer('poll_enabled')->default(1);
			$table->string('admin_password');
			$table->timestamp('admin_password_set_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('noise_floor')->default(0);
			$table->string('signal')->default('0');
			$table->string('txfreq')->default('0');
			$table->string('rxfreq')->default('0');
			$table->string('qam')->default('0');
			$table->string('wireless_mode')->default('0');
			$table->string('signal1')->default('0');
			$table->string('signal2')->default('0');
			$table->string('md5_username');
			$table->string('md5_password');
			$table->integer('antenna_id')->default(1);
			$table->integer('antenna_heading');
			$table->integer('antenna_tilt');
			$table->string('ftp_port')->default('21');
			$table->string('api_port')->default('8728');
			$table->string('ssh_port')->default('22');
			$table->string('http_port')->default('80');
			$table->string('winbox_port')->default('8291');
			$table->string('telnet_port')->default('23');
			$table->text('license_1', 65535);
			$table->text('license_2', 65535);
			$table->integer('last_download_test')->default(0);
			$table->integer('last_upload_test')->default(0);
			$table->string('last_speed_time')->default('0');
			$table->string('snmp_community', 64)->default('public');
			$table->integer('include_interfaces')->default(1);
			$table->integer('psu1')->default(1);
			$table->integer('psu2')->default(1);
			$table->integer('upstream_device_id')->nullable();
			$table->string('dl_util')->default('0');
			$table->string('ul_util')->default('0');
			$table->string('max_dl_util')->default('0');
			$table->string('max_ul_util')->default('0');
			$table->timestamp('update_started')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('previous_serial_nr')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('devices');
	}

}
