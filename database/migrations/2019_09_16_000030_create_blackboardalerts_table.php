<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlackboardalertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blackboardalerts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('device_id');
			$table->integer('acknowledged');
			$table->string('alert_severity');
			$table->string('alert_type');
			$table->string('alert_value');
			$table->string('alert_message');
			$table->timestamps();
			$table->integer('location_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blackboardalerts');
	}

}
