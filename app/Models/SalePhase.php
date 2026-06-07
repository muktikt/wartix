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
}