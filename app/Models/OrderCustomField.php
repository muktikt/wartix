<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomField extends Model
{
    protected $fillable = ['order_id', 'custom_field_id', 'value'];

    protected $casts = ['value' => 'encrypted'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}