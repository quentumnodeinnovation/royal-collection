<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'address',
        'city',
        'pincode',
        'payment_method',
        'subtotal',
        'shipping',
        'total',
        'status',
    ];

    /**
     * Optional customer account attached to this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Line items inside this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
