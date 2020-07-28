<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_designs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title_style')->nullable();
            $table->text('address_style')->nullable();
            $table->text('image_style')->nullable();
            $table->text('date_style')->nullable();
            $table->text('city_style')->nullable();
            $table->text('price_style')->nullable();
            $table->text('places_style')->nullable();
            $table->integer('event_id')->nullable();
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
        Schema::dropIfExists('ticket_designs');
    }
}
