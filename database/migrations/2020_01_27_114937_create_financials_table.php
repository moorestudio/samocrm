<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id');
            $table->longText('income')->nullable();
            $table->longText('consuption')->nullable();
            $table->integer('total')->default(0);
            $table->integer('total_income')->default(0);
            $table->integer('total_consuption')->default(0);
            $table->mediumText('franch_percent')->nullable();
            $table->integer('franch_total')->default(0);
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
        Schema::dropIfExists('financials');
    }
}
