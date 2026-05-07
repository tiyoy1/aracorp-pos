<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class TransactionItem extends Model
{
    protected $fillable = ['quantity', 'price'];

    public function transaction(): BelongsTo {
        return $this->belongsTo(Transaction::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    #[Override]
    protected static function booted()
    {   
        static::created(function ($item) {
            $product = $item->product;

            if ($product->stock < $item->quantity) {
                throw new \Exception('Stock ga cukup');
            }
            $product->decrement('stock', $item->quantity);  
        });
    }
}
//$product->decrement()
//product.decrement()
//product.stock < item.quantity