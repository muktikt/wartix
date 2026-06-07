<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessLog extends Model
{
    protected $fillable = [
        'order_id', 'event_id', 'sale_phase_id', 'ticket_category_id',
        'email', 'username', 'qty', 'status', 'raw_report',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function salePhase()
    {
        return $this->belongsTo(SalePhase::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}