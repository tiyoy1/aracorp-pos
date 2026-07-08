<?php

namespace App\Observers;

use App\Models\ReturnItem;
use App\Models\StockMovement;

class ReturnItemObserver
{
    public function creating(ReturnItem $returnItem): void
    {
        $transactionItem = $returnItem->transactionItem;

        // Validate: can't return more than what's still returnable on this line
        if ($returnItem->quantity > $transactionItem->returnableQuantity()) {
            throw new \RuntimeException(
                "Cannot return {$returnItem->quantity} units — only {$transactionItem->returnableQuantity()} remaining returnable for this item."
            );
        }

        // Refund at the ORIGINAL sale price, not today's price
        $returnItem->refund_amount = $returnItem->quantity * $transactionItem->price;
    }

    public function created(ReturnItem $returnItem): void
    {
        $transactionItem = $returnItem->transactionItem;

        // Stock comes back in — reuses the exact same StockMovementObserver
        // that already handles increments/decrements everywhere else
        StockMovement::create([
            'transaction_item_id' => $transactionItem->id,
            'product_id'          => $transactionItem->product_id,
            'type'                => 'in',
            'quantity'            => $returnItem->quantity,
            'note'                => 'Return #' . $returnItem->returnTransaction->invoice_number,
        ]);

        $this->updateReturnTotal($returnItem);
    }

    public function updated(ReturnItem $returnItem): void
    {
        $this->updateReturnTotal($returnItem);
    }

    public function deleted(ReturnItem $returnItem): void
    {
        $this->updateReturnTotal($returnItem);
    }

    private function updateReturnTotal(ReturnItem $returnItem): void
    {
        $returnTransaction = $returnItem->returnTransaction;
        $returnTransaction->update([
            'total_refund' => $returnTransaction->items->sum('refund_amount'),
        ]);
    }
}