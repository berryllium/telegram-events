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
        Schema::table('author_telegram_bot', function (Blueprint $table) {
            $table->boolean('can_select_channels')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_telegram_bot', function (Blueprint $table) {
            $table->dropColumn('can_select_channels');
        });
    }
};
