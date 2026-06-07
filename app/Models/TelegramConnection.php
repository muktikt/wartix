<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramConnection extends Model
{
    protected $fillable = [
        'order_id', 'telegram_user_id', 'telegram_chat_id',
        'telegram_username', 'connected_at',
    ];

    protected $casts = ['connected_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}