<?php

namespace App\Observers;

use App\Models\PurchaseOrderItem;

class PurchaseOrderItemObserver
{
    public function creating(PurchaseOrderItem $item): void
    {
        $item->subtotal = $item->quantity * $item->cost_price;
    }

    public function created(PurchaseOrderItem $item): void
    {
        $this->updatePurchaseOrderTotal($item);
    }

    public function updated(PurchaseOrderItem $item): void
    {
        $this->updatePurchaseOrderTotal($item);
    }

    public function deleted(PurchaseOrderItem $item): void
    {
        $this->updatePurchaseOrderTotal($item);
    }

    private function updatePurchaseOrderTotal(PurchaseOrderItem $item): void
    {
        $po = $item->purchaseOrder;
        $po->update([
            'total_cost' => $po->items->sum('subtotal'),
        ]);
    }
}