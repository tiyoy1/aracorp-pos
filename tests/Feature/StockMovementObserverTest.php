<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_item_decrements_stock_once_through_stock_movement(): void
    {
        $product = Product::create([
            'name' => 'Keyboard',
            'price' => 50000,
            'stock' => 100,
        ]);

        $transaction = Transaction::create([
            'invoice_number' => 'INV-001',
        ]);

        $transactionItem = TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertSame(98, $product->refresh()->stock);
        $this->assertDatabaseHas('stock_movements', [
            'transaction_item_id' => $transactionItem->id,
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 2,
        ]);
    }

    public function test_in_stock_movement_increases_stock(): void
    {
        $product = Product::create([
            'name' => 'Monitor',
            'price' => 1500000,
            'stock' => 100,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 2,
        ]);

        $this->assertSame(102, $product->refresh()->stock);
    }
}
