<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationauditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locationaudits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('location_id');
			$table->string('location_name');
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
		Schema::drop('locationaudits');
	}

}
