<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Enum diubah ke string supaya menambah platform baru (mis. "goers", "fasticket")
     * ke depannya tidak perlu migration ALTER ENUM lagi. Validasi nilai
     * yang diperbolehkan tetap dijaga di level controller (EventBuilderController).
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('platform_type', 20)->default('tiketcom')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('platform_type', ['tiketcom', 'loket', 'yesplis', 'custom'])->default('tiketcom')->change();
        });
    }
};
