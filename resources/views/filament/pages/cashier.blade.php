<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem;">

        {{-- LEFT — Product List --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">

            {{-- Search --}}
            <input
                type="text"
                wire:model.live="search"
                placeholder="🔍 Cari produk..."
                style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem; background: #1f2937; border: 1px solid #374151; color: white; font-size: 0.875rem;"
            />

            {{-- Product Grid --}}
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; max-height: 600px; overflow-y: auto;">
                @forelse($this->getProducts() as $product)
                    <button
                        wire:click="addToCart({{ $product->id }})"
                        style="background: #1f2937; border: 1px solid #374151; border-radius: 0.75rem; padding: 1rem; text-align: left; cursor: pointer; transition: border-color 0.2s;"
                        onmouseover="this.style.borderColor='#6366f1'"
                        onmouseout="this.style.borderColor='#374151'"
                    >
                        <p style="font-weight: 600; color: white; font-size: 0.875rem;">
                            {{ $product->name }}
                        </p>
                        <p style="color: #818cf8; font-weight: 700; font-size: 0.875rem; margin-top: 0.5rem;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <p style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem;">
                            Stok: {{ $product->stock }}
                        </p>
                    </button>
                @empty
                    <div style="grid-column: span 3; text-align: center; color: #6b7280; padding: 3rem;">
                        <p style="font-size: 2rem;">📦</p>
                        <p>Produk tidak ditemukan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT — Cart --}}
        <div style="background: #1f2937; border: 1px solid #374151; border-radius: 1rem; padding: 1.25rem; display: flex; flex-direction: column; height: 700px;">

            {{-- Cart Header --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.125rem; font-weight: 700; color: white;">🛒 Keranjang</h2>
                @if(!empty($cart))
                    <button
                        wire:click="clearCart"
                        style="font-size: 0.75rem; color: #f87171; background: none; border: none; cursor: pointer;"
                    >
                        Hapus Semua
                    </button>
                @endif
            </div>

            {{-- Cart Items --}}
            <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.5rem;">
                @forelse($cart as $productId => $item)
                    <div style="background: #111827; border-radius: 0.75rem; padding: 0.75rem 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <p style="color: white; font-size: 0.875rem; font-weight: 500; flex: 1;">
                                {{ $item['name'] }}
                            </p>
                            <button
                                wire:click="removeFromCart({{ $productId }})"
                                style="color: #6b7280; background: none; border: none; cursor: pointer; font-size: 0.75rem; margin-left: 0.5rem;"
                            >
                                ✕
                            </button>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {{-- Quantity Controls --}}
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <button
                                    wire:click="decreaseQty({{ $productId }})"
                                    style="width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #374151; color: white; border: none; cursor: pointer; font-size: 1rem;"
                                >−</button>
                                <span style="color: white; font-size: 0.875rem; font-weight: 700; min-width: 1.5rem; text-align: center;">
                                    {{ $item['quantity'] }}
                                </span>
                                <button
                                    wire:click="addToCart({{ $productId }})"
                                    style="width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #374151; color: white; border: none; cursor: pointer; font-size: 1rem;"
                                >+</button>
                            </div>
                            {{-- Subtotal --}}
                            <p style="color: #818cf8; font-weight: 700; font-size: 0.875rem;">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #6b7280; padding: 3rem 0;">
                        <p style="font-size: 2rem;">🛒</p>
                        <p style="font-size: 0.875rem;">Keranjang kosong</p>
                        <p style="font-size: 0.75rem; margin-top: 0.25rem;">Klik produk untuk menambahkan</p>
                    </div>
                @endforelse
            </div>

            {{-- Total + Confirm --}}
            <div style="border-top: 1px solid #374151; padding-top: 1rem; margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <span style="color: #9ca3af; font-size: 0.875rem;">Total Pembayaran</span>
                    <span style="color: white; font-size: 1.25rem; font-weight: 700;">
                        Rp {{ number_format($this->getTotal(), 0, ',', '.') }}
                    </span>
                </div>
                <button
                    wire:click="confirmOrder"
                    @if(empty($cart)) disabled @endif
                    style="width: 100%; background: #4f46e5; color: white; font-weight: 600; padding: 0.75rem; border-radius: 0.75rem; border: none; cursor: pointer; opacity: {{ empty($cart) ? '0.4' : '1' }};"
                >
                    ✅ Konfirmasi Transaksi
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>