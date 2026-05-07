<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock'];

    public function stock(): HasMany {
        return $this->hasMany(StockMovement::class);
    }

    public function transaction_item(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }
}

