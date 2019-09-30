<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBGPPeersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('b_g_p_peers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('remote_address');
			$table->string('remote_as');
			$table->string('state', 64);
			$table->string('default_originate', 64);
			$table->string('prefix_count', 64);
			$table->string('disabled', 64);
			$table->string('uptime', 64);
			$table->string('device_id');
			$table->timestamps();
			$table->integer('acknowledged')->nullable()->default(0);
			$table->string('ack_note', 64);
			$table->integer('ack_user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('b_g_p_peers');
	}

}
