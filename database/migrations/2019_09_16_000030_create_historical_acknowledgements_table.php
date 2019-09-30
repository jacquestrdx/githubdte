<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHistoricalAcknowledgementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('historical_acknowledgements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ack_note');
			$table->string('user_id');
			$table->string('location_id');
			$table->integer('device_id')->default(0);
			$table->integer('fault_id')->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('historical_acknowledgements');
	}

}
