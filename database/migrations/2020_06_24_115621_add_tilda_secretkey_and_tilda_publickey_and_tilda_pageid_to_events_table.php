<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTildaSecretkeyAndTildaPublickeyAndTildaPageidToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
          $table->text('tilda_publickey')->nullable();
          $table->text('tilda_secretkey')->nullable();
          $table->integer('tilda_pageid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
          $table->dropColumn(['tilda_publickey','tilda_secretkey','tilda_pageid']);
        });
    }
}
