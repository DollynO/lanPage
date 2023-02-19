<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentRoundUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_round_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('tournament_round_id');
            $table->bigInteger('user_id');
            $table->integer('points');
            $table->boolean('hasWon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_round_user');
    }
}
