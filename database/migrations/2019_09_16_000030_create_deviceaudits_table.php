<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceauditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deviceaudits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('device_id');
			$table->string('device_name');
			$table->string('device_ip');
			$table->string('action');
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
		Schema::drop('deviceaudits');
	}

}
