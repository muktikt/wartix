<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCategoryChoice extends Model
{
    protected $fillable = [
        'order_id',
        'ticket_category_id',
        'priority',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}