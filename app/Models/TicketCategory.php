<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $fillable = [
        'event_id', 'name', 'fee_per_ticket', 'ticket_price',
        'payment_mode', 'custom_payment_amount', 'max_qty',
        'slot_limit', 'payment_timeout', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}