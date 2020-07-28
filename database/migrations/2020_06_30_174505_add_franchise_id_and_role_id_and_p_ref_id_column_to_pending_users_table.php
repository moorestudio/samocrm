<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFranchiseIdAndRoleIdAndPRefIdColumnToPendingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pending_users', function (Blueprint $table) {
          $table->text('franchise_id')->nullable();
          $table->text('role_id')->nullable();
          $table->text('p_ref_id')->nullable();
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
          $table->dropColumn(['franchise_id','role_id','p_ref_id']);
        });
    }
}
