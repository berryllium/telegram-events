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
        Schema::table('channel_message_schedule', function (Blueprint $table) {
            $table->integer('tries')->default(0)->after('error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_message_schedule', function (Blueprint $table) {
            $table->dropColumn('tries');
        });
    }
};
