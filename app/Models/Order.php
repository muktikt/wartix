<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'event_id', 'sale_phase_id', 'ticket_category_id',
        'qty', 'title', 'full_name', 'phone_number', 'email',
        'identity_number', 'telegram_username', 'telegram_user_id',
        'telegram_chat_id', 'service_fee_total', 'ticket_price_total',
        'admin_fee', 'grand_total', 'payment_mode', 'payment_status',
        'order_status', 'notes',
    ];

    protected $casts = [
        'identity_number' => 'encrypted',
    ];

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

    public function guests()
    {
        return $this->hasMany(OrderGuest::class)->orderBy('ticket_position');
    }

    public function customFieldAnswers()
    {
        return $this->hasMany(OrderCustomField::class);
    }

    public function successLog()
    {
        return $this->hasOne(SuccessLog::class);
    }

    public function paymentLog()
    {
        return $this->hasOne(PaymentLog::class)->latest();
    }

    public function telegramConnection()
    {
        return $this->hasOne(TelegramConnection::class);
    }
}