<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use App\Models\StockMovement;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "updated" event.
     * Fires when status changes to 'received'
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->wasChanged('status') && $purchaseOrder->status === 'received') {
            $this->receiveStock($purchaseOrder);
        }
    }

    private function receiveStock(PurchaseOrder $purchaseOrder): void
    {
        foreach ($purchaseOrder->items as $item) {
            // Create stock movement — StockMovementObserver handles the actual stock increment
            StockMovement::create([
                'product_id' => $item->product_id,
                'type'       => 'in',
                'quantity'   => $item->quantity,
                'note'       => 'PO #' . $purchaseOrder->invoice_number,
            ]);

            // Update product's current cost price to the latest purchase cost
            $item->product->update([
                'cost_price' => $item->cost_price,
            ]);
        }
    }
}