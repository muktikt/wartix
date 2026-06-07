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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('artist_name');
            $table->string('banner_image')->nullable();
            $table->string('seatplan_image')->nullable();
            $table->text('description')->nullable();
            $table->string('venue');
            $table->string('city');
            $table->string('event_type');
            $table->datetime('event_date');
            $table->enum('status', ['upcoming', 'ongoing', 'finished'])->default('upcoming');
            $table->enum('platform_type', ['tiketcom', 'loket', 'yesplis', 'custom'])->default('tiketcom');
            $table->integer('max_ticket_per_order')->default(4);
            $table->enum('checkout_type', ['user_account_checkout', 'managed_checkout'])->default('managed_checkout');
            $table->boolean('guest_enabled')->default(false);
            $table->enum('guest_mode', ['single_buyer', 'multi_guest'])->default('single_buyer');
            $table->boolean('guest_identity_only')->default(true);
            $table->boolean('same_title_for_guest')->default(true);
            $table->boolean('require_unique_identity_number')->default(true);
            $table->enum('identity_mode', ['nik_only', 'custom_identity'])->default('nik_only');
            $table->string('telegram_group_link')->nullable();
            $table->integer('slot_availability')->nullable();
            $table->timestamps();

            $table->fullText([
                    'title',
                    'artist_name',
                    'description',
                    'venue',
                    'city',
                    'event_type'
                ], 'events_search_fulltext');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
