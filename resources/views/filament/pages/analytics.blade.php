<x-filament-panels::page>
<style>
    .analytics-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .analytics-topbar {
        display: flex;
        justify-content: flex-end;
    }

    .refresh-btn {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        color: #475569;
        padding: 0.5rem 1rem;
        border-radius: 0.625rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }
    .refresh-btn:hover {
        border-color: #10B981;
        color: #10B981;
        background: #F0FDF4;
    }

    .offline-card {
        background: #FFF7ED;
        border: 1.5px solid #FED7AA;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
    }

    /* ── STAT CARDS ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .stat-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
    }
    .stat-card.green::before { background: #10B981; }
    .stat-card.blue::before  { background: #3B82F6; }
    .stat-card.purple::before { background: #8B5CF6; }
    .stat-card.amber::before { background: #F59E0B; }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94A3B8;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -0.025em;
        line-height: 1;
    }
    .stat-value.green  { color: #059669; }
    .stat-value.blue   { color: #2563EB; }
    .stat-value.purple { color: #7C3AED; }
    .stat-value.amber  { color: #D97706; }

    /* ── CONTENT GRID ── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .data-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .data-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #F1F5F9;
        background: #F8FAFC;
        font-weight: 700;
        color: #0F172A;
        font-size: 0.9rem;
    }
    .data-card-body { padding: 0 1.5rem; }

    .data-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem 0;
        border-bottom: 1px solid #F1F5F9;
    }
    .data-row:last-child { border-bottom: none; }

    .rank-badge {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: #F1F5F9;
        color: #64748B;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
    .rank-badge.gold   { background: #FEF3C7; color: #D97706; }
    .rank-badge.silver { background: #F1F5F9; color: #64748B; }
    .rank-badge.bronze { background: #FEF0E7; color: #B45309; }

    .data-name {
        color: #0F172A;
        font-size: 0.875rem;
        font-weight: 500;
        flex: 1;
    }
    .data-right { text-align: right; }
    .data-primary {
        font-weight: 700;
        color: #0F172A;
        font-size: 0.875rem;
    }
    .data-secondary {
        font-size: 0.75rem;
        color: #94A3B8;
        margin-top: 0.125rem;
    }

    /* ── LOW STOCK ── */
    .low-stock-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        padding: 1rem 1.5rem;
    }
    .stock-pill {
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
        border: 1.5px solid;
    }
    .stock-pill.danger  { background: #FFF5F5; border-color: #FCA5A5; }
    .stock-pill.warning { background: #FFFBEB; border-color: #FCD34D; }
    .stock-pill-name { font-size: 0.8rem; font-weight: 500; color: #0F172A; }
    .stock-pill-count {
        font-size: 1.25rem;
        font-weight: 800;
        margin-top: 0.25rem;
    }
    .stock-pill.danger  .stock-pill-count { color: #EF4444; }
    .stock-pill.warning .stock-pill-count { color: #F59E0B; }

    .empty-state {
        text-align: center;
        padding: 2.5rem;
        color: #94A3B8;
        font-size: 0.875rem;
    }
</style>

<div class="analytics-wrapper">

    {{-- Top Bar --}}
    <div class="analytics-topbar">
        <button class="refresh-btn" wire:click="refresh">
            🔄 Refresh
        </button>
    </div>

    @if(empty($summary))
        {{-- Offline State --}}
        <div class="offline-card">
            <p style="font-size: 2rem;">⚠️</p>
            <p style="font-weight: 600; color: #92400E; margin-top: 0.5rem;">
                Analytics server is offline
            </p>
            <p style="color: #B45309; font-size: 0.875rem; margin-top: 0.25rem;">
                Run <code style="background: #FEF3C7; padding: 0.2rem 0.5rem; border-radius: 0.375rem;">python analytics.py</code> to start it
            </p>
        </div>
    @else

        {{-- Stat Cards --}}
        <div class="stat-grid">
            <div class="stat-card green">
                <p class="stat-label">Today's Revenue</p>
                <p class="stat-value green">
                    Rp {{ number_format($today['revenue'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="stat-card blue">
                <p class="stat-label">Today's Transactions</p>
                <p class="stat-value blue">
                    {{ $today['total_transactions'] ?? 0 }}
                </p>
            </div>
            <div class="stat-card purple">
                <p class="stat-label">Total Revenue</p>
                <p class="stat-value purple">
                    Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="stat-card amber">
                <p class="stat-label">Avg Transaction</p>
                <p class="stat-value amber">
                    Rp {{ number_format($summary['average_transaction'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Best Sellers + Daily Revenue --}}
        <div class="content-grid">

            {{-- Best Sellers --}}
            <div class="data-card">
                <div class="data-card-header">🏆 Best Selling Products</div>
                <div class="data-card-body">
                    @forelse($bestSellers as $index => $item)
                        <div class="data-row">
                            <div style="display:flex; align-items:center; flex:1;">
                                <div class="rank-badge {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <span class="data-name">{{ $item['name'] }}</span>
                            </div>
                            <div class="data-right">
                                <p class="data-primary">{{ $item['total_sold'] }} sold</p>
                                <p class="data-secondary">
                                    Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">No sales data yet</div>
                    @endforelse
                </div>
            </div>

            {{-- Daily Revenue --}}
            <div class="data-card">
                <div class="data-card-header">📈 Daily Revenue — Last 7 Days</div>
                <div class="data-card-body">
                    @forelse($dailyRevenue as $day)
                        <div class="data-row">
                            <span class="data-name" style="color: #64748B;">
                                {{ \Carbon\Carbon::parse($day['date'])->format('D, d M') }}
                            </span>
                            <div class="data-right">
                                <p class="data-primary">
                                    Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                                </p>
                                <p class="data-secondary">
                                    {{ $day['total_transactions'] }} transactions
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">No revenue data yet</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Low Stock --}}
        @if(!empty($lowStock))
            <div class="data-card">
                <div class="data-card-header">⚠️ Low Stock Alert</div>
                <div class="low-stock-grid">
                    @foreach($lowStock as $product)
                        <div class="stock-pill {{ $product['stock'] <= 3 ? 'danger' : 'warning' }}">
                            <p class="stock-pill-name">{{ $product['name'] }}</p>
                            <p class="stock-pill-count">{{ $product['stock'] }} left</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @endif
</div>
</x-filament-panels::page>