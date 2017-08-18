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
            $table->integer('status')->default(0)->index('FK_status_master_status_idx');
            $table->dateTime('date_join')->nullable();
            $table->dateTime('date_expired')->nullable();
            $table->string('token')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('license')->nullable();
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
