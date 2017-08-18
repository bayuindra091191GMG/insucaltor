<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMasterUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('master_users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('first_name', 45)->nullable();
			$table->string('last_name', 45)->nullable();
			$table->integer('agency')->nullable()->index('FK_agency_master_agency_idx');
			$table->integer('status')->default(0)->index('FK_status_master_status_idx');
			$table->dateTime('date_join')->nullable();
			$table->dateTime('date_expired')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('master_users');
	}

}
