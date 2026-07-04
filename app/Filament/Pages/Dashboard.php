<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Dashboard extends Page  // ← extend Page, not BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected string $view = 'filament.pages.dashboard';
    protected static ?string $navigationLabel = 'Overview';
    protected static ?string $title = 'Overview';

    protected static ?int $navigationSort = -1; // ← keeps it at top
    protected static string|\UnitEnum|null $navigationGroup = '';

    public array $summary = [];
    public array $bestSellers = [];
    public array $dailyRevenue = [];
    public array $lowStock = [];
    public array $today = [];

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
            $this->summary = [];
            $this->bestSellers = [];
            $this->dailyRevenue = [];
            $this->lowStock = [];
            $this->today = [];
        }
    }

    public function refresh(): void
    {
        $this->loadAnalytics();
    }
}