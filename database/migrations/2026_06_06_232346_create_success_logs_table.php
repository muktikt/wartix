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
        Schema::create('success_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sale_phase_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ticket_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->integer('qty')->default(1);
            $table->string('status')->default('success');
            $table->text('raw_report')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_logs');
    }
};
