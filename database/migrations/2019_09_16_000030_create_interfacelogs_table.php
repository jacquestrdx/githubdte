<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInterfacelogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('interfacelogs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('status');
			$table->string('device_id');
			$table->timestamps();
			$table->integer('dinterface_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('interfacelogs');
	}

}
