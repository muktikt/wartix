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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_phase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_category_id')->constrained()->cascadeOnDelete();
            $table->integer('qty')->default(1);
            $table->string('title')->nullable();
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email');
            $table->text('identity_number')->nullable();
            $table->string('telegram_username')->nullable();
            $table->string('telegram_user_id')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->bigInteger('service_fee_total')->default(0);
            $table->bigInteger('ticket_price_total')->default(0);
            $table->bigInteger('admin_fee')->default(0);
            $table->bigInteger('grand_total')->default(0);
            $table->enum('payment_mode', ['service_fee_only', 'full_payment', 'custom_payment'])->default('service_fee_only');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->enum('order_status', ['waiting', 'processing', 'success', 'failed', 'cancelled'])->default('waiting');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_code');
            $table->index('event_id');
            $table->index('sale_phase_id');
            $table->index('ticket_category_id');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('email');
            $table->index('phone_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
