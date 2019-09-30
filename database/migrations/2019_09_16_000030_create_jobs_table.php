<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('date')->nullable();
			$table->string('location_id')->nullable();
			$table->string('technician')->nullable();
			$table->string('reg_nr', 45)->nullable();
			$table->string('time_spent', 45)->nullable();
			$table->string('km', 45)->nullable();
			$table->string('fault_description', 45)->nullable();
			$table->string('resolution')->nullable();
			$table->integer('fiz_live')->nullable()->default(0);
			$table->string('signal', 45)->nullable();
			$table->string('pi_down', 45)->nullable();
			$table->string('pi_up', 45)->nullable();
			$table->string('mweb_down', 45)->nullable();
			$table->string('mweb_up', 45)->nullable();
			$table->string('created_at', 45)->nullable();
			$table->string('updated_at', 45)->nullable()->default('CURRENT_TIMESTAMP()');
			$table->string('ccq', 45)->nullable();
			$table->string('pi_latency', 45)->nullable();
			$table->string('mweb_latency', 45)->nullable();
			$table->string('start_km', 45)->nullable();
			$table->string('end_km', 45)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');
	}

}
