<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventlogs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('remote_table_id')->default(0);
			$table->string('remote_table')->default('0');
			$table->string('current_value')->default('0');
			$table->string('previous_value')->default('0');
			$table->string('event_type')->default('0');
			$table->string('severity')->default('0');
			$table->integer('status')->default(1);
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
		Schema::drop('eventlogs');
	}

}
