<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendPrimaryKeyPartyUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('party_user', function (Blueprint $table) {
            $table->dropColumn('id');
            //$table->dropPrimary('id');
            $table->primary(['party_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('party_user', function (Blueprint $table) {
            $table->dropPrimary(['party_id', 'user_id']);
            $table->id();
        });
    }
}
