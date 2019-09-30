<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name', 65535);
			$table->text('lat', 65535)->nullable();
			$table->text('lng', 65535)->nullable();
			$table->timestamps();
			$table->string('batteries')->nullable()->default('1');
			$table->string('standbytime')->nullable()->default('1');
			$table->string('mainbackhaul')->nullable();
			$table->string('backupbackhaul')->nullable();
			$table->string('site_type')->nullable()->default('normal');
			$table->integer('bwstaff_id')->nullable()->default(1);
			$table->integer('hscontact_id')->nullable()->default(1);
			$table->integer('status')->nullable();
			$table->integer('clients')->unsigned()->nullable();
			$table->string('WISP', 45)->nullable()->default('0');
			$table->integer('acknowledged')->default(0);
			$table->string('mainbackhaultype', 45)->nullable()->default('wireless-5.8');
			$table->string('backupbackhaultype', 45)->nullable()->default('wireless');
			$table->integer('subnet')->default(0);
			$table->string('description', 64);
			$table->string('noise_floor', 11)->default('-85');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('locations');
	}

}
