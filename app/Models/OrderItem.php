<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'size',
        'quantity',
        'unit_price',
        'line_total',
    ];

    /**
     * Parent order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Linked catalog product if still active.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
