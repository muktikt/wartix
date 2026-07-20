<?php
namespace App\Models;

use App\Models\Scopes\HideUnlinkedOrdersScope;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'event_id', 'sale_phase_id', 'ticket_category_id',
        'qty', 'title', 'full_name', 'phone_number', 'email',
        'identity_number', 'telegram_username', 'telegram_user_id',
        'telegram_chat_id', 'telegram_link_token', 'telegram_linked_at',
        'service_fee_total', 'ticket_price_total',
        'admin_fee', 'grand_total', 'payment_mode', 'payment_status',
        'order_status', 'notes', 'membership_code',
    ];

    protected $casts = [
        'identity_number'    => 'encrypted',
        'telegram_linked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new HideUnlinkedOrdersScope());

        static::saved(function ($order) {
            $order->syncEventStatus();
        });

        static::deleted(function ($order) {
            $order->syncEventStatus();
        });
    }

    public function syncEventStatus(): void
    {
        $event = $this->event;
        if ($event) {
            $available = $event->resolved_available_slots;
            if ($available !== null) {
                if ($available <= 0 && $event->status === 'upcoming') {
                    $event->update(['status' => 'slot_penuh']);
                } elseif ($available > 0 && $event->status === 'slot_penuh') {
                    $event->update(['status' => 'upcoming']);
                }
            }
        }
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

    public function categoryChoices()
    {
        return $this->hasMany(OrderCategoryChoice::class)->orderBy('priority');
    }
}