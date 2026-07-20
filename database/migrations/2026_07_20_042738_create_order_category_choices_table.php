<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_category_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_category_id')->constrained()->cascadeOnDelete();
            $table->integer('priority')->default(1);
            $table->timestamps();

            $table->unique(['order_id', 'ticket_category_id']);
            $table->index(['order_id', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_category_choices');
    }
};