<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_suggestions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('played')->default(false);
            $table->unique(['game_id','party_id','user_id']);
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_sugestions');
    }
}
