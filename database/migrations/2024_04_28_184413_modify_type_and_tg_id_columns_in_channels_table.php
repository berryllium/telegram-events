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
        Schema::table('channels', function (Blueprint $table) {
            $table->enum('type', ['tg', 'vk', 'ok', 'in', 'wp'])->change();
            $table->string('tg_id')->comment('ID канала')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->enum('type', ['tg', 'vk', 'ok', 'in'])->change();
            $table->bigInteger('tg_id')->comment('ID телеграм канала')->change();
        });
    }
};
