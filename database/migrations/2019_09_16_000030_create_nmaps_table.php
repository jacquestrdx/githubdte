<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNmapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nmaps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description');
			$table->string('subnet');
			$table->string('port_1')->default('0');
			$table->string('port_2')->default('0');
			$table->string('port_3')->default('0');
			$table->string('port_4')->default('0');
			$table->string('port_5')->default('0');
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
		Schema::drop('nmaps');
	}

}
