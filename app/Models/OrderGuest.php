<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGuest extends Model
{
    protected $fillable = [
        'order_id', 'ticket_position', 'guest_type',
        'title', 'full_name', 'identity_number',
    ];

    protected $casts = [
        'identity_number' => 'encrypted',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}