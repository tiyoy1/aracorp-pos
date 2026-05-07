<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class StockMovement extends Model
{
    protected $fillable = ['product_id','type', 'quantity', 'note'];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
