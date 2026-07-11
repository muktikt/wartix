<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'order_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Create a notification for a new order.
     */
    public static function notifyNewOrder(Order $order): self
    {
        $eventName = $order->event->title ?? 'event';

        return static::create([
            'type'     => 'order_created',
            'title'    => 'Order Baru Masuk',
            'message'  => "{$order->full_name} order {$order->qty} tiket untuk {$eventName}",
            'icon'     => 'cart',
            'color'    => 'indigo',
            'link'     => route('admin.orders.show', $order->id),
            'order_id' => $order->id,
        ]);
    }

    /**
     * Create a notification when payment is received.
     */
    public static function notifyPaymentPaid(Order $order): self
    {
        return static::create([
            'type'     => 'payment_paid',
            'title'    => 'Fee Sudah Dibayar',
            'message'  => "{$order->full_name} sudah bayar fee Rp " . number_format($order->grand_total) . " ({$order->order_code})",
            'icon'     => 'cash',
            'color'    => 'green',
            'link'     => route('admin.orders.show', $order->id),
            'order_id' => $order->id,
        ]);
    }

    /**
     * Create a notification when a ticket success is reported.
     */
    public static function notifySuccessReport(Order $order): self
    {
        return static::create([
            'type'     => 'success_report',
            'title'    => 'Tiket Berhasil',
            'message'  => "Tiket {$order->order_code} berhasil didapatkan untuk {$order->full_name}",
            'icon'     => 'check',
            'color'    => 'emerald',
            'link'     => route('admin.orders.show', $order->id),
            'order_id' => $order->id,
        ]);
    }
}
