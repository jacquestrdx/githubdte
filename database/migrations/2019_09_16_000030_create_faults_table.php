<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFaultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faults', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('device_id');
			$table->text('description');
			$table->integer('acknowledged')->default(0);
			$table->timestamps();
			$table->integer('status')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('faults');
	}

}
