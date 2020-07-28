<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('franch_count')->default(1);
            $table->integer('client_count')->default(1);
            $table->boolean('scheme')->default(1);
            $table->integer('ticket_count')->nullable();
            $table->boolean('info')->default(1);
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
            $table->dropColumn(['franch_count', 'client_count', 'scheme', 'ticket_count', 'info']);
        });
    }
}
