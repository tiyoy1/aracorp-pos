<x-filament-panels::page>
<style>
    .pos-wrapper {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 1.5rem;
        min-height: 85vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* ── LEFT PANEL ── */
    .pos-left { display: flex; flex-direction: column; gap: 1rem; }

    .pos-search {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.75rem;
        background: #FFFFFF;
        color: #0F172A;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .pos-search:focus { border-color: #10B981; }

    .search-wrapper { position: relative; }
    .search-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 1rem;
        pointer-events: none;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        max-height: 65vh;
        overflow-y: auto;
        padding-right: 0.25rem;
    }

    .product-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.875rem;
        padding: 1rem;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .product-card:hover {
        border-color: #10B981;
        box-shadow: 0 4px 12px rgba(16,185,129,0.12);
        transform: translateY(-1px);
    }
    .product-card:active { transform: translateY(0); }

    .product-name {
        font-weight: 600;
        color: #0F172A;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .product-price {
        color: #10B981;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .product-stock {
        color: #94A3B8;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    .product-stock.low { color: #F59E0B; }
    .product-stock.critical { color: #EF4444; }

    .empty-products {
        grid-column: span 3;
        text-align: center;
        padding: 3rem;
        color: #94A3B8;
    }

    /* ── RIGHT PANEL (Receipt style) ── */
    .pos-right {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .cart-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1.5px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #F8FAFC;
    }
    .cart-title {
        font-weight: 700;
        color: #0F172A;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .cart-clear {
        font-size: 0.75rem;
        color: #EF4444;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        transition: background 0.15s;
    }
    .cart-clear:hover { background: #FEF2F2; }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 0.75rem 1.5rem;
    }

    .cart-item {
        padding: 0.875rem 0;
        border-bottom: 1px dashed #E2E8F0;
    }
    .cart-item:last-child { border-bottom: none; }

    .cart-item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .cart-item-name {
        font-weight: 500;
        color: #0F172A;
        font-size: 0.875rem;
        flex: 1;
    }
    .cart-item-remove {
        color: #CBD5E1;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        transition: all 0.15s;
        margin-left: 0.5rem;
    }
    .cart-item-remove:hover { color: #EF4444; background: #FEF2F2; }

    .cart-item-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 2rem;
        padding: 0.25rem 0.5rem;
    }
    .qty-btn {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
        background: #FFFFFF;
        color: #475569;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
    .qty-btn:hover { background: #10B981; color: white; }
    .qty-value {
        font-weight: 700;
        color: #0F172A;
        font-size: 0.875rem;
        min-width: 1.25rem;
        text-align: center;
    }
    .cart-item-subtotal {
        font-weight: 700;
        color: #0F172A;
        font-size: 0.875rem;
    }

    .cart-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #94A3B8;
    }
    .cart-empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
    .cart-empty-text { font-size: 0.875rem; font-weight: 500; color: #64748B; }
    .cart-empty-sub { font-size: 0.75rem; margin-top: 0.25rem; }

    /* ── RECEIPT FOOTER ── */
    .cart-footer {
        border-top: 1.5px solid #E2E8F0;
        padding: 1.25rem 1.5rem;
        background: #F8FAFC;
    }

    .cart-divider {
        border: none;
        border-top: 1px dashed #CBD5E1;
        margin: 0.75rem 0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .total-label { color: #64748B; font-size: 0.875rem; }
    .total-amount {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -0.025em;
    }

    .confirm-btn {
        width: 100%;
        background: #10B981;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem;
        border-radius: 0.75rem;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 2px 8px rgba(16,185,129,0.3);
    }
    .confirm-btn:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16,185,129,0.4);
        transform: translateY(-1px);
    }
    .confirm-btn:active { transform: translateY(0); }
    .confirm-btn:disabled {
        background: #E2E8F0;
        color: #94A3B8;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
</style>

<div class="pos-wrapper">

    {{-- LEFT — Products --}}
    <div class="pos-left">

        {{-- Search --}}
        <div class="search-wrapper">
            <span class="search-icon">🔍</span>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search products..."
                class="pos-search"
            />
        </div>

        {{-- Product Grid --}}
        <div class="product-grid">
            @forelse($this->getProducts() as $product)
                <button
                    class="product-card"
                    wire:click="addToCart({{ $product->id }})"
                >
                    <p class="product-name">{{ $product->name }}</p>
                    <p class="product-price">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="product-stock {{ $product->stock <= 3 ? 'critical' : ($product->stock <= 10 ? 'low' : '') }}">
                        {{ $product->stock }} in stock
                    </p>
                </button>
            @empty
                <div class="empty-products">
                    <p style="font-size: 2rem;">📦</p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem;">No products found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT — Cart --}}
    <div class="pos-right">

        {{-- Cart Header --}}
        <div class="cart-header">
            <span class="cart-title">
                🧾 Order
                @if(!empty($cart))
                    <span style="background: #10B981; color: white; font-size: 0.7rem; padding: 0.125rem 0.5rem; border-radius: 1rem;">
                        {{ collect($cart)->sum('quantity') }} items
                    </span>
                @endif
            </span>
            @if(!empty($cart))
                <button class="cart-clear" wire:click="clearCart">
                    Clear all
                </button>
            @endif
        </div>

        {{-- Cart Items --}}
        <div class="cart-items">
            @forelse($cart as $productId => $item)
                <div class="cart-item">
                    <div class="cart-item-top">
                        <span class="cart-item-name">{{ $item['name'] }}</span>
                        <button
                            class="cart-item-remove"
                            wire:click="removeFromCart({{ $productId }})"
                        >✕</button>
                    </div>
                    <div class="cart-item-bottom">
                        <div class="qty-controls">
                            <button
                                class="qty-btn"
                                wire:click="decreaseQty({{ $productId }})"
                            >−</button>
                            <span class="qty-value">{{ $item['quantity'] }}</span>
                            <button
                                class="qty-btn"
                                wire:click="addToCart({{ $productId }})"
                            >+</button>
                        </div>
                        <span class="cart-item-subtotal">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="cart-empty">
                    <div class="cart-empty-icon">🧾</div>
                    <p class="cart-empty-text">No items yet</p>
                    <p class="cart-empty-sub">Click a product to add it</p>
                </div>
            @endforelse
        </div>

        {{-- Cart Footer --}}
        <div class="cart-footer">
            <hr class="cart-divider">
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-amount">
                    Rp {{ number_format($this->getTotal(), 0, ',', '.') }}
                </span>
            </div>
            <button
                class="confirm-btn"
                wire:click="confirmOrder"
                @if(empty($cart)) disabled @endif
            >
                ✓ Confirm Order
            </button>
        </div>

    </div>
</div>
</x-filament-panels::page>