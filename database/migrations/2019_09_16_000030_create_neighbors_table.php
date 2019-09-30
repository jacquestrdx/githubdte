<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNeighborsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('neighbors', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ip', 64)->nullable();
			$table->string('mac_address', 64)->nullable();
			$table->string('identity', 64)->nullable();
			$table->string('platform', 64)->nullable();
			$table->integer('device_id')->default(0);
			$table->string('interface', 64)->default('0');
			$table->timestamps();
			$table->integer('verified')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('neighbors');
	}

}
