<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'cost_price', 'stock'];

    public function stockMovement(): HasMany {
        return $this->hasMany(StockMovement::class);
    }

    public function transaction_item(): HasMany {
        return $this->hasMany(TransactionItem::class);
    }

    public function purchaseOrderItems(): HasMany {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}

