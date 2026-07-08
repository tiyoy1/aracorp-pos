<?php

namespace App\Observers;

use App\Models\ReturnTransaction;
use Illuminate\Support\Facades\Auth;

class ReturnTransactionObserver
{
    public function creating(ReturnTransaction $returnTransaction): void
    {
        if (empty($returnTransaction->invoice_number)) {
            $returnTransaction->invoice_number = 'RTN-' . now()->format('Ymd-His');
        }

        if (empty($returnTransaction->cashier_id)) {
            $returnTransaction->cashier_id = Auth::id();
        }
    }
}