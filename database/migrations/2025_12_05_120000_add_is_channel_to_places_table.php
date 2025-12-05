<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            if (!Schema::hasColumn('places', 'is_channel')) {
                $table->boolean('is_channel')->default(false)->after('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            if (Schema::hasColumn('places', 'is_channel')) {
                $table->dropColumn('is_channel');
            }
        });
    }
};
