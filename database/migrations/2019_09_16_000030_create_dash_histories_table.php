<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDashHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dash_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('active_pppoe');
			$table->string('max_pppoe');
			$table->string('down_devices');
			$table->string('problem_devices');
			$table->string('power_monitors_down');
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
		Schema::drop('dash_histories');
	}

}
