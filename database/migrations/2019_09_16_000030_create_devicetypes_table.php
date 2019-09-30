<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicetypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devicetypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('sub_type', 64);
			$table->timestamps();
			$table->integer('volts')->default(0);
			$table->integer('amps')->default(0);
			$table->integer('watts')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('devicetypes');
	}

}
