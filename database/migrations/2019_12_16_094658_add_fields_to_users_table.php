<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('city')->nullable();
            $table->string('contacts')->nullable();
            $table->bigInteger('INN')->nullable();
            $table->dateTime('contract_date')->nullable();
            $table->string('contract')->nullable();
            $table->longText('comments')->nullable();
            $table->integer('franchise_id')->nullable();
            $table->string('franchise_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
