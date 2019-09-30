<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDashboarditemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dashboarditems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dashboard_id');
			$table->string('description');
			$table->string('type');
			$table->timestamps();
			$table->string('item_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dashboarditems');
	}

}
