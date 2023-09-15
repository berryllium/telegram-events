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
        Schema::create('message_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Message::class);
            $table->dateTime('sending_date');
            $table->char('status', 20);
            $table->text('error_text');
            $table->comment('messages to send');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_schedules');
    }
};
