<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('telegram_link_token')->unique()->nullable()->after('telegram_chat_id');
            $table->timestamp('telegram_linked_at')->nullable()->after('telegram_link_token');
        });

        // Tambah 'pending_link' ke enum order_status
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY order_status ENUM('pending_link', 'waiting', 'processing', 'success', 'failed', 'cancelled') DEFAULT 'pending_link'");
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['telegram_link_token', 'telegram_linked_at']);
        });

        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY order_status ENUM('waiting', 'processing', 'success', 'failed', 'cancelled') DEFAULT 'waiting'");
        }
    }
};