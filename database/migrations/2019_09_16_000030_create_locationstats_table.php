<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationstatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locationstats', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('location_id');
			$table->integer('active_pppoes');
			$table->integer('backhualtx');
			$table->integer('backhualrx');
			$table->integer('stations');
			$table->integer('sectors');
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
		Schema::drop('locationstats');
	}

}
