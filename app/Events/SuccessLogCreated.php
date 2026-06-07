<?php
namespace App\Events;

use App\Models\Order;
use App\Models\SuccessLog;
use App\Services\MaskService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuccessLogCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $publicData;
    public array $adminData;

    public function __construct(SuccessLog $log, Order $order)
    {
        // Public data — tersensor
        $this->publicData = [
            'id'       => $log->id,
            'status'   => 'SUCCESS',
            'email'    => MaskService::email($order->email),
            'event'    => $order->event->title ?? '-',
            'phase'    => $order->salePhase->name ?? '-',
            'category' => $order->ticketCategory->name ?? '-',
            'qty'      => $order->qty,
            'time'     => now()->diffForHumans(),
        ];

        // Admin data — lengkap
        $this->adminData = [
            'id'           => $log->id,
            'status'       => 'SUCCESS',
            'order_code'   => $order->order_code,
            'email'        => $order->email,
            'full_name'    => $order->full_name,
            'event'        => $order->event->title ?? '-',
            'phase'        => $order->salePhase->name ?? '-',
            'category'     => $order->ticketCategory->name ?? '-',
            'qty'          => $order->qty,
            'grand_total'  => $order->grand_total,
            'time'         => now()->diffForHumans(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('success-monitor-public'),
            new Channel('success-monitor-admin'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'success.log.created';
    }
}