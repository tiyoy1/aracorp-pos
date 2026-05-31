<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\TransactionItem;

class TransactionItemObserver
{
    /**
     * Handle the TransactionItem "created" event.
     */
    public function created(TransactionItem $transactionItem): void
    {
        StockMovement::create([
            'transaction_item_id' => $transactionItem->id,
            'product_id' => $transactionItem->product_id,
            'type' => 'out',
            'quantity' => $transactionItem->quantity,
            'note' => 'Transaction #0'.$transactionItem->transaction_id,
        ]);
        $this->updateTransactionTotal($transactionItem);
    }

    public function creating(TransactionItem $transactionItem): void
    {
        $product = $transactionItem->product;

        if ($product->stock < $transactionItem->quantity) {
            throw new \Exception("Insufficient stock for product : {$product->name}");
        }

        $transactionItem->price = $product->price;
        $transactionItem->subtotal = $transactionItem->price * $transactionItem->quantity;
    }

    /**
     * Handle the TransactionItem "updated" event.
     */
    public function updated(TransactionItem $transactionItem): void
    {
        if ($transactionItem->wasChanged('quantity')) {
            $transactionItem->stockMovement()->update([
                'quantity' => $transactionItem->quantity,
            ]);
        }
        $this->updateTransactionTotal($transactionItem);
    }

    /**
     * Handle the TransactionItem "deleted" event.
     */
    public function deleted(TransactionItem $transactionItem): void
    {
        $transactionItem->stockMovement()->delete();
        $this->updateTransactionTotal($transactionItem);
    }

    /**
     * Handle the TransactionItem "restored" event.
     */
    public function restored(TransactionItem $transactionItem): void
    {
        //
    }

    /**
     * Handle the TransactionItem "force deleted" event.
     */
    public function forceDeleted(TransactionItem $transactionItem): void
    {
        //
    }

    private function updateTransactionTotal(TransactionItem $transactionItem): void
    {
        $transaction = $transactionItem->transaction;
        $transaction->update([
            'total_price' => $transaction->transactionItems->sum('subtotal'),
        ]);
    }
}
