<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Transaction extends Model
{
    protected $fillable = ['invoice_number','total_price'];

    public function transactionItems(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }

    #[Override]
    public function __toString()
    {
        return $this->invoice_number;
    }
}
