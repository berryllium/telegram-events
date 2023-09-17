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
        Schema::create('message_schedules_telegram_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\MessageSchedule::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\TelegramChannel::class);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_schedules_telegram_channels');
    }
};
