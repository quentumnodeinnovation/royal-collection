<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'session_token',
        'user_id',
        'customer_name',
        'customer_email',
        'status',
        'item_count',
        'subtotal',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'item_count' => 'integer',
            'subtotal' => 'integer',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Associated customer if authenticated.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Line items currently in this cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
