<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInterfacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('interfaces', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name')->default('0');
			$table->string('default_name')->default('0');
			$table->string('mac_address', 45)->nullable()->default('none');
			$table->string('type')->default('none');
			$table->integer('threshhold')->nullable()->default(100);
			$table->string('last_link_down_time', 45)->nullable();
			$table->string('last_link_up_time', 45)->nullable();
			$table->string('mtu', 45)->nullable();
			$table->string('actual_mtu', 45)->nullable();
			$table->string('running', 45)->nullable();
			$table->string('previous_running_state', 64);
			$table->string('link_speed', 64)->default('0');
			$table->string('previous_link_speed', 64);
			$table->string('disabled', 45)->nullable();
			$table->integer('device_id')->nullable();
			$table->integer('txspeed')->default(0);
			$table->integer('rxspeed')->default(0);
			$table->timestamps();
			$table->integer('threshholds_today')->nullable()->default(0);
			$table->integer('acknowledged')->nullable()->default(0);
			$table->integer('maxtxspeed');
			$table->integer('maxrxspeed');
			$table->integer('dashboard_id');
			$table->string('title');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('interfaces');
	}

}
