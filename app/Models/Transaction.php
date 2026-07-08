<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Override;

class Transaction extends Model
{
    protected $fillable = ['invoice_number','total_price', 'cashier_id'];

    public function transactionItems(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function returnTransactions(): HasMany
    {
        return $this->hasMany(ReturnTransaction::class);
    }

    #[Override]
    public function __toString()
    {
        return $this->invoice_number;
    }
}
