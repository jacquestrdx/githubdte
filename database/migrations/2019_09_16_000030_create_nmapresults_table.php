<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNmapresultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nmapresults', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ip');
			$table->string('port_1');
			$table->string('port_2');
			$table->string('port_3');
			$table->string('port_4');
			$table->string('port_5');
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
		Schema::drop('nmapresults');
	}

}
