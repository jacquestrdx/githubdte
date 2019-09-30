<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotifbackupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifbackups', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->default(0);
			$table->string('message');
			$table->string('type');
			$table->string('done');
			$table->integer('device_id');
			$table->integer('client_id');
			$table->timestamps();
			$table->integer('epoch');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifbackups');
	}

}
