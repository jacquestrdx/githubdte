<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePppoeclientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pppoeclients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('ip');
			$table->string('uptime');
			$table->string('mac');
			$table->string('vendor');
			$table->string('type', 64)->nullable()->default('pppoe');
			$table->integer('device_id');
			$table->integer('is_online')->default(1);
			$table->timestamp('last_seen')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('is_notified')->default(0);
			$table->timestamps();
			$table->string('statable_id')->nullable();
			$table->string('reason')->nullable();
			$table->string('download_speed');
			$table->string('upload_speed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pppoeclients');
	}

}
