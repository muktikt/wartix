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
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['telegram_link_token', 'telegram_linked_at']);
        });
    }
};