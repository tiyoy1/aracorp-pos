<?php

namespace App\Observers;

use App\Models\StockMovement;

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement): void
    {
        $product = $stockMovement->product;

        if ($stockMovement->type === 'in') {
            $product->increment('stock', $stockMovement->quantity);

            return;
        }

        if ($product->stock < $stockMovement->quantity) {
            throw new \Exception("Insufficient stock for product : {$product->name}");
        }

        $product->decrement('stock', $stockMovement->quantity);
    }

    /**
     * Handle the StockMovement "updated" event.
     */
    public function updated(StockMovement $stockMovement): void
    {
        //
    }

    public function updating(StockMovement $stockMovement): void
    {
        $product = $stockMovement->product;

        $oldQuantity = $stockMovement->getOriginal('quantity');
        $oldType = $stockMovement->getOriginal('type');

        if ($oldType == 'out') {
            $product->increment('stock', $oldQuantity);
        } else {
            $product->decrement('stock', $oldQuantity);
        }

        if ($stockMovement->type == 'in') {
            $product->increment('stock', $stockMovement->quantity);
        } else {
            $product->decrement('stock', $stockMovement->quantity);
        }
    }

    /**
     * Handle the StockMovement "deleted" event.
     */
    public function deleted(StockMovement $stockMovement): void
    {
        $product = $stockMovement->product;

        if ($stockMovement->type == 'in') {
            $product->decrement('stock', $stockMovement->quantity);
        } else {
            $product->increment('stock', $stockMovement->quantity);
        }
    }

    /**
     * Handle the StockMovement "restored" event.
     */
    public function restored(StockMovement $stockMovement): void
    {
        //
    }

    /**
     * Handle the StockMovement "force deleted" event.
     */
    public function forceDeleted(StockMovement $stockMovement): void
    {
        //
    }
}
