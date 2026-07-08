<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnTransaction extends Model
{
    protected $fillable = ['transaction_id', 'cashier_id', 'invoice_number', 'total_refund', 'reason'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function __toString(): string
    {
        return $this->invoice_number;
    }
}
