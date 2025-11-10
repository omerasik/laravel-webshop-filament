<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'price',
        'order_status',
        'payment_status',
        'payment_method',
        'shipping_address',
    ];

    protected $casts = [
        'price' => 'float',
        'created_at' => 'datetime',
    ];

    /** De bestelling hoort bij één gebruiker. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Alle bestelregels die bij deze bestelling horen. */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
