<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveToPartnerReferralRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_referral_relationships', function (Blueprint $table) {
            $table->integer('active')->nullable()->default(null);
            $table->integer('paid')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_referral_relationships', function (Blueprint $table) {
            $table->dropColumn(['active','paid']);
        });
    }
}
