<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInterfaceWarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('interface_warnings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dinterface_id');
			$table->string('message');
			$table->string('threshold')->default('0');
			$table->string('time');
			$table->timestamps();
			$table->integer('ack')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('interface_warnings');
	}

}
