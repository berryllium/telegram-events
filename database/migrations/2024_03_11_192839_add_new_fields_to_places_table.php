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
        Schema::table('places', function (Blueprint $table) {
            $table->string('link_whatsapp')->nullable()->default(null);
            $table->string('link_tg')->nullable()->default(null);
            $table->string('link_ok')->nullable()->default(null);
            $table->string('link_vk')->nullable()->default(null);
            $table->string('link_instagram')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('link_whatsapp');
            $table->dropColumn('link_tg');
            $table->dropColumn('link_ok');
            $table->dropColumn('link_vk');
            $table->dropColumn('link_instagram');
        });
    }
};
