<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMasterUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('master_users', function(Blueprint $table)
		{
			$table->foreign('agency', 'FK_agency_master_agency')->references('id')->on('master_agencies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status', 'FK_status_master_status')->references('id')->on('master_statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('master_users', function(Blueprint $table)
		{
			$table->dropForeign('FK_agency_master_agency');
			$table->dropForeign('FK_status_master_status');
		});
	}

}
