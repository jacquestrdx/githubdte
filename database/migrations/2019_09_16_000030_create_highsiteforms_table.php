<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHighsiteformsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('highsiteforms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('location_id');
			$table->string('ticket_nr');
			$table->string('user_ids');
			$table->string('job_to_do');
			$table->string('job_done');
			$table->string('time_started');
			$table->string('time_ended');
			$table->string('notes');
			$table->timestamps();
			$table->integer('highsite_visit_category_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('highsiteforms');
	}

}
