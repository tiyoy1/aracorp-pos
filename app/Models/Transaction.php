<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = ['total_price'];

    public function transaction_item(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }
}
