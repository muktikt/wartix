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
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->bigInteger('fee_per_ticket')->default(0);
            $table->bigInteger('ticket_price')->default(0);
            $table->enum('payment_mode', ['service_fee_only', 'full_payment', 'custom_payment'])->default('service_fee_only');
            $table->bigInteger('custom_payment_amount')->nullable();
            $table->integer('max_qty')->default(4);
            $table->integer('slot_limit')->nullable();
            $table->integer('payment_timeout')->default(10);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
