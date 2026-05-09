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
        $this->updateTransactionTotal($transactionItem);
        $transactionItem->transaction->increment('total_price', $transactionItem->subtotal);
        
        StockMovement::create([
            'product_id' => $transactionItem->product_id,
            'type' => 'out',
            'quantity' => $transactionItem->quantity,
            'note' => 'Transaction #0' . $transactionItem->transaction_id,
        ]);
    }

    public function creating(TransactionItem $transactionItem) : void
    {
        $transactionItem->price = $transactionItem->product->price;
        $transactionItem->subtotal = $transactionItem->price * $transactionItem->quantity;
    }

    /**
     * Handle the TransactionItem "updated" event.
     */
    public function updated(TransactionItem $transactionItem): void
    {
        $this->updateTransactionTotal($transactionItem);
    }

    /**
     * Handle the TransactionItem "deleted" event.
     */
    public function deleted(TransactionItem $transactionItem): void
    {
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

    private function updateTransactionTotal (TransactionItem $transactionItem) : void 
    {
        $transaction = $transactionItem->transaction;
        $transaction->update([
            'total_price' => $transaction->transaction_item->sum('subtotal')
        ]);
    }
}
