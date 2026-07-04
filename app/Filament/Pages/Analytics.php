<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Analytics extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected string $view = 'filament.pages.analytics';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $title = '📊 Sales Analytics';

    // All analytics data
    public array $summary = [];
    public array $bestSellers = [];
    public array $dailyRevenue = [];
    public array $lowStock = [];
    public array $today = [];

    // Python analytics base URL
    protected string $analyticsUrl = 'http://localhost:5001';

    public function mount(): void
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics(): void
    {
        try {
            $this->summary = Http::timeout(5)
                ->get("{$this->analyticsUrl}/analytics/summary")
                ->json() ?? [];

            $this->bestSellers = Http::timeout(5)
                ->get("{$this->analyticsUrl}/analytics/best-sellers")
                ->json() ?? [];

            $this->dailyRevenue = Http::timeout(5)
                ->get("{$this->analyticsUrl}/analytics/daily-revenue")
                ->json() ?? [];

            $this->lowStock = Http::timeout(5)
                ->get("{$this->analyticsUrl}/analytics/low-stock")
                ->json() ?? [];

            $this->today = Http::timeout(5)
                ->get("{$this->analyticsUrl}/analytics/today")
                ->json() ?? [];

        } catch (\Exception $e) {
            // Python server unavailable — fail gracefully
            $this->summary = [];
            $this->bestSellers = [];
            $this->dailyRevenue = [];
            $this->lowStock = [];
            $this->today = [];
        }
    }

    // Refresh button
    public function refresh(): void
    {
        $this->loadAnalytics();
    }
}