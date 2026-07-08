<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    protected $fillable = ['return_transaction_id', 'transaction_item_id', 'quantity', 'refund_amount'];

    public function returnTransaction(): BelongsTo
    {
        return $this->belongsTo(ReturnTransaction::class);
    }

    public function transactionItem(): BelongsTo
    {
        return $this->belongsTo(TransactionItem::class);
    }
}
