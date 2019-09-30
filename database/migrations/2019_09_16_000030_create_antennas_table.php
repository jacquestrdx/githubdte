<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAntennasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('antennas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description', 64)->default('0');
			$table->string('gain');
			$table->string('vertical');
			$table->string('horizontal');
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
		Schema::drop('antennas');
	}

}
