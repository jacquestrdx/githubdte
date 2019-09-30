<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThresholdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thresholds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('device_id')->default(0);
			$table->integer('interface_id')->default(0);
			$table->string('value_id')->default('0');
			$table->string('value')->default('0');
			$table->string('level')->default('0');
			$table->string('active_time')->default('0');
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
		Schema::drop('thresholds');
	}

}
