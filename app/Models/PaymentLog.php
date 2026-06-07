<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'payment_reference', 'qris_url',
        'amount', 'status', 'expired_at', 'paid_at', 'callback_payload',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'callback_payload' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}