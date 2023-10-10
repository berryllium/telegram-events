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
        Schema::create('channel_message_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\MessageSchedule::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Channel::class)->constrained()->onDelete('cascade');
            $table->boolean('sent')->default(false);
            $table->text('error')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_message_schedule');
    }
};
