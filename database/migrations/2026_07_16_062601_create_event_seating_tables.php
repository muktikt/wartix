<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('row_name');
            $table->integer('seat_number');
            $table->string('category'); // e.g., VIP, Premium, Standard
            $table->decimal('price', 10, 2);
            $table->string('status')->default('available'); // available, held, booked
            $table->string('hold_session_id')->nullable();
            $table->timestamp('hold_expires_at')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            // SVG positioning coordinates
            $table->integer('cx'); 
            $table->integer('cy');
            $table->integer('r')->default(12);
            $table->timestamps();
            
            $table->unique(['event_id', 'row_name', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
