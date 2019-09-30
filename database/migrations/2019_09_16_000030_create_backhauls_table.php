<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBackhaulsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('backhauls', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('location_id');
			$table->integer('to_location_id');
			$table->integer('priority')->default(0);
			$table->integer('linked_to_interface')->nullable();
			$table->timestamps();
			$table->integer('dinterface_id')->nullable();
			$table->string('description', 64)->default('');
			$table->integer('backhaultype_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('backhauls');
	}

}
