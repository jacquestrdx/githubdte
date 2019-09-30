<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomsnmpoidsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customsnmpoids', function(Blueprint $table)
		{
			$table->integer('device_id')->default(0);
			$table->text('oid_to_poll');
			$table->text('return_value');
			$table->increments('id');
			$table->timestamps();
			$table->string('snmp_community')->default('public');
			$table->string('value_name')->default('');
			$table->string('math')->default('* 1');
			$table->integer('poll')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customsnmpoids');
	}

}
