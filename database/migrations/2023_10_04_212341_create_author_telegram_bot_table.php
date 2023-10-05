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
        Schema::create('author_telegram_bot', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Author::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\TelegramBot::class)->constrained()->onDelete('cascade');
            $table->boolean('trusted')->default(false);
            $table->string('title')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_telegram_bot');
    }
};
