<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'artist_name', 'banner_image', 'seatplan_image',
        'description', 'venue', 'city', 'event_type', 'event_date', 'status',
        'platform_type', 'max_ticket_per_order', 'checkout_type',
        'guest_enabled', 'guest_mode', 'guest_identity_only',
        'same_title_for_guest', 'require_unique_identity_number',
        'identity_mode', 'telegram_group_link', 'slot_availability',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'guest_enabled' => 'boolean',
        'guest_identity_only' => 'boolean',
        'same_title_for_guest' => 'boolean',
        'require_unique_identity_number' => 'boolean',
    ];

    public function salePhases()
    {
        return $this->hasMany(SalePhase::class)->orderBy('sort_order');
    }

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class)->orderBy('sort_order');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class)->orderBy('sort_order');
    }

    public function successLogs()
    {
        return $this->hasMany(SuccessLog::class);
    }
}