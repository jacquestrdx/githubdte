<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVoipclientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('voipclients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('location_id');
			$table->string('ping');
			$table->string('last_latency');
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
		Schema::drop('voipclients');
	}

}
