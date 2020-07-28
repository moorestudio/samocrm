<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartColumnsToFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financials', function (Blueprint $table) {
            $table->integer('samo_percent')->nullable()->after('franch_total');
            $table->integer('event_percent')->nullable()->after('samo_percent');
            $table->integer('speaker_percent')->nullable()->after('event_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financials', function (Blueprint $table) {
            $table->dropColumn('samo_percent', 'event_percent', 'speaker_percent');
        });
    }
}
