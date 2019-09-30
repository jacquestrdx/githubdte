<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePossibleBackhaulsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('possible_backhauls', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('from_device_id');
			$table->integer('to_device_id');
			$table->timestamps();
			$table->integer('added_to_backhauls')->nullable()->default(0);
			$table->integer('from_location')->nullable();
			$table->integer('to_location')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('possible_backhauls');
	}

}
