<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('mac')->unique('mac_UNIQUE');
			$table->string('ip');
			$table->string('latency');
			$table->string('ccq');
			$table->string('signal');
			$table->string('tx_rate');
			$table->string('rx_rate')->default('0');
			$table->string('rates')->nullable()->default('0');
			$table->integer('device_id')->unsigned();
			$table->timestamps();
			$table->string('time', 45);
			$table->integer('txsignal')->default(0);
			$table->integer('rxsignal')->default(0);
			$table->string('distance')->default('0');
			$table->string('model', 64)->nullable();
			$table->string('uptime', 64)->nullable();
			$table->string('mode')->nullable();
			$table->integer('status')->nullable()->default(0);
			$table->string('tx_snr', 64)->default('0');
			$table->string('rx_snr', 64)->default('0');
			$table->string('tx_utilization', 64)->default('0');
			$table->string('rx_utilization', 64)->default('0');
			$table->string('tx_max_utilization', 64)->default('0');
			$table->string('rx_max_utilization', 64)->default('0');
			$table->string('disconnects', 64)->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('statables');
	}

}
