<?php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;

    public function __construct(Order $order)
    {
        $this->data = [
            'order_id'       => $order->id,
            'order_code'     => $order->order_code,
            'payment_status' => $order->payment_status,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders-admin'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.status.updated';
    }
}