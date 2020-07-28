<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayTypeColumnAndPendingUsersPaymentIdAndIndempotenceColumnToPendingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pending_users', function (Blueprint $table) {
          $table->text('pay_type')->nullable();
          $table->text('payment_id')->nullable();
          $table->text('idempotence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending_users', function (Blueprint $table) {
          $table->dropColumn(['pay_type','payment_id','idempotence']);
        });
    }
}
