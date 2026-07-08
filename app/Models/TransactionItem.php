<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransactionItem extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price', 'cost_price', 'subtotal'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function returnItems(): HasMany
{
    return $this->hasMany(ReturnItem::class);
}

// How many units of this line have already been returned
public function returnedQuantity(): int
{
    return $this->returnItems()->sum('quantity');
}

// How many units are still eligible to be returned
public function returnableQuantity(): int
{
    return $this->quantity - $this->returnedQuantity();
}
}
