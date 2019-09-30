<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAcknowledgementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acknowledgements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ack_note');
			$table->string('device_id');
			$table->integer('fault_id');
			$table->string('user_id');
			$table->integer('location_id')->nullable();
			$table->string('bgppeer_id');
			$table->integer('voipserver_id');
			$table->integer('active')->default(0);
			$table->timestamps();
			$table->integer('blackboard_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('acknowledgements');
	}

}
