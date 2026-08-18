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
        'event_date'                     => 'date:Y-m-d',
        'guest_enabled'                  => 'boolean',
        'guest_identity_only'            => 'boolean',
        'same_title_for_guest'           => 'boolean',
        'require_unique_identity_number' => 'boolean',
        'slot_availability'              => 'integer',
    ];

    protected $appends = ['banner_url', 'seatplan_url'];

    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_image) {
            return null;
        }
        if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
            return $this->banner_image;
        }
        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($this->banner_image);
    }

    public function getSeatplanUrlAttribute(): ?string
    {
        if (!$this->seatplan_image) {
            return null;
        }
        if (str_starts_with($this->seatplan_image, 'http://') || str_starts_with($this->seatplan_image, 'https://')) {
            return $this->seatplan_image;
        }
        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($this->seatplan_image);
    }

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

    public function getResolvedTotalSlotsAttribute(): ?int
    {
        if ($this->slot_availability !== null) {
            return (int) $this->slot_availability;
        }

        $phaseHasLimit = $this->relationLoaded('salePhases')
            ? $this->salePhases->contains(fn (SalePhase $phase) => $phase->slot_limit !== null)
            : $this->salePhases()->whereNotNull('slot_limit')->exists();

        $categoryHasLimit = $this->relationLoaded('ticketCategories')
            ? $this->ticketCategories->contains(fn (TicketCategory $category) => $category->slot_limit !== null)
            : $this->ticketCategories()->whereNotNull('slot_limit')->exists();

        $totals = [];

        if ($phaseHasLimit) {
            $totals[] = $this->relationLoaded('salePhases')
                ? (int) $this->salePhases->sum(fn (SalePhase $phase) => (int) $phase->slot_limit)
                : (int) $this->salePhases()->whereNotNull('slot_limit')->sum('slot_limit');
        }

        if ($categoryHasLimit) {
            $totals[] = $this->relationLoaded('ticketCategories')
                ? (int) $this->ticketCategories->sum(fn (TicketCategory $category) => (int) $category->slot_limit)
                : (int) $this->ticketCategories()->whereNotNull('slot_limit')->sum('slot_limit');
        }

        return $totals !== [] ? min($totals) : null;
    }

    public function getResolvedAvailableSlotsAttribute(): ?int
    {
        $totalSlots = $this->resolved_total_slots;

        if ($totalSlots === null) {
            return null;
        }

        $usedSlots = Order::withoutGlobalScopes()
            ->where('event_id', $this->id)
            ->whereNotIn('order_status', ['failed', 'cancelled'])
            ->count();

        return max(0, $totalSlots - $usedSlots);
    }
}
