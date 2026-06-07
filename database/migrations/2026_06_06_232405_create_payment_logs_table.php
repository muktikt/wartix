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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('dompetx');
            $table->string('payment_reference')->unique()->nullable();
            $table->string('qris_url')->nullable();
            $table->bigInteger('amount')->default(0);
            $table->enum('status', ['unpaid', 'pending', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamps();

            $table->index('payment_reference');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
