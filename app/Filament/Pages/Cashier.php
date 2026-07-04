<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Pages\Page;
use Filament\Support\Icons\Icon;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class Cashier extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected string $view = 'filament.pages.cashier';
    protected static ?string $navigationLabel = 'Cashier';
    protected static ?string $title = '🛒 Cashier';

    // Search state
    public string $search = '';

    // Cart state — keyed by product_id
    public array $cart = [];

    // Get filtered products
    public function getProducts(): Collection
    {
        return Product::query()
            ->where('stock', '>', 0)
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->get();
    }

    // Add product to cart
    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        // Check against actual stock
        $currentQty = $this->cart[$productId]['quantity'] ?? 0;

        if ($currentQty >= $product->stock) {
            Notification::make()
                ->title("Stok {$product->name} tidak cukup!")
                ->danger()
                ->send();
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            $this->cart[$productId]['subtotal'] =
                $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
        } else {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => 1,
                'subtotal'   => $product->price,
            ];
        }
    }

    // Decrease quantity or remove if reaches 0
    public function decreaseQty(int $productId): void
    {
        if (!isset($this->cart[$productId])) return;

        if ($this->cart[$productId]['quantity'] <= 1) {
            $this->removeFromCart($productId);
            return;
        }

        $this->cart[$productId]['quantity']--;
        $this->cart[$productId]['subtotal'] =
            $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
    }

    // Remove item completely
    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    // Clear entire cart
    public function clearCart(): void
    {
        $this->cart = [];
    }

    // Calculate total
    public function getTotal(): int
    {
        return collect($this->cart)->sum('subtotal');
    }

    // Confirm and save transaction
    public function confirmOrder(): void
    {
        if (empty($this->cart)) {
            Notification::make()
                ->title('Keranjang masih kosong!')
                ->warning()
                ->send();
            return;
        }

        // Generate invoice number
        $invoice = 'INV-' . now()->format('Ymd-His');

        // Create transaction
        $transaction = Transaction::create([
            'invoice_number' => $invoice,
            'total_price'    => $this->getTotal(),
        ]);

        // Create each transaction item
        // TransactionItemObserver handles stock deduction automatically
        foreach ($this->cart as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $item['product_id'],
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
                'subtotal'       => $item['subtotal'],
            ]);
        }

        // Clear cart after successful order
        $this->cart = [];
        $this->search = '';

        Notification::make()
            ->title('Transaksi berhasil! 🎉')
            ->body("Invoice: {$invoice}")
            ->success()
            ->send();
    }
}