<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePhase extends Model
{
    protected $fillable = [
        'event_id', 'name', 'start_time', 'end_time',
        'status', 'slot_limit', 'description', 'sort_order',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getAvailableSlotsAttribute(): ?int
    {
        if ($this->slot_limit === null) {
            return null;
        }

        $used = $this->orders()->whereNotIn('order_status', ['failed', 'cancelled'])->sum('qty');
        return max(0, $this->slot_limit - $used);
    }
}