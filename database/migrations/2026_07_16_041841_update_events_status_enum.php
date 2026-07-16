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
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE events MODIFY status ENUM('upcoming', 'slot_penuh', 'ongoing', 'finished') DEFAULT 'upcoming'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE events MODIFY status ENUM('upcoming', 'ongoing', 'finished') DEFAULT 'upcoming'");
        }
    }
};
