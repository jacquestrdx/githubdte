<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('name');
			$table->string('ip');
			$table->string('reseller');
			$table->string('devicetype_id');
			$table->string('location_id');
			$table->string('radius_usage', 64)->default('0');
			$table->string('radius_cap', 64)->default('0');
			$table->integer('ping')->default(1);
			$table->integer('ping1')->default(1);
			$table->integer('ping2')->default(1);
			$table->integer('ping3')->default(1);
			$table->string('lastseen', 64);
			$table->string('lastdown', 64);
			$table->integer('downs_today')->default(0);
			$table->timestamps();
			$table->string('comment', 64);
			$table->integer('is_enterprise')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
