<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->longText('payload')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');

        Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
                $table->dropColumn('email');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->text('payload')->change();
        });
    }
};
