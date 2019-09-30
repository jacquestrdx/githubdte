<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSipaccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sipaccounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 100);
			$table->string('shortnumber');
			$table->string('longnumber');
			$table->string('currentip');
			$table->integer('status_id')->default(3);
			$table->string('historicalip');
			$table->string('model');
			$table->integer('sipserver_id');
			$table->dateTime('lastupdate')->default('0000-00-00 00:00:00');
			$table->integer('ack')->default(0);
			$table->integer('client_id')->default(0);
			$table->integer('upstreamTrunk')->default(0);
			$table->integer('ping1')->nullable()->default(0);
			$table->timestamps();
			$table->dateTime('lastonline')->default('0000-00-00 00:00:00');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sipaccounts');
	}

}
