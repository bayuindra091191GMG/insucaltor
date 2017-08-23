<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email');
            $table->string('password');
            $table->string('first_name', 45)->nullable();
            $table->string('last_name', 45)->nullable();
            $table->integer('agency')->nullable()->index('FK_agency_master_agency_idx');
            $table->dateTime('date_join')->nullable();
            $table->dateTime('date_expired')->nullable();
            $table->string('photo', 80)->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('license', 45)->nullable();
            $table->string('device_imei', 100)->nullable();
            $table->integer('status')->default(0)->index('FK_status_master_status_idx');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
