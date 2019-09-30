<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsernotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usernotifications', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('notification_id');
			$table->integer('user_id');
			$table->integer('completed')->nullable()->default(0);
			$table->integer('is_read')->default(0);
			$table->timestamps();
			$table->integer('device_id');
			$table->integer('interfacewarning_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usernotifications');
	}

}
