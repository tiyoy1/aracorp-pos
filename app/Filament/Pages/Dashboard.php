<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public array $revenueChart = [];
    public array $categoryChart = [];
    public array $hourlyChart = [];
    public array $profitSummary = [];
    public array $profitToday = [];
    public array $profitTrend = [];
    public array $profitByProduct = [];

    protected string $analyticsUrl = 'http://localhost:5001';

//     public static function canAccess(): bool
// {
//     /** @var User|null $user */
//     $user = Auth::user();

//     return $user?->hasAnyRole(['owner', 'manager']) ?? false;
// }

    public function mount(): void
{
    /** @var User|null $user */
    $user = Auth::user();

    if ($user && $user->hasRole('cashier') && !$user->hasAnyRole(['owner', 'manager'])) {
        $this->redirect('/admin/cashier');
        return;
    }

    $this->loadAnalytics();
}

    public function loadAnalytics(): void
    {
        try {

        $this->profitSummary = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/profit-summary")
    ->json() ?? [];

$this->profitToday = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/profit-today")
    ->json() ?? [];

$this->profitTrend = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/profit-trend")
    ->json() ?? [];

$this->profitByProduct = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/profit-by-product")
    ->json() ?? [];
            
            $this->revenueChart = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/revenue-chart")
    ->json() ?? [];

$this->categoryChart = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/category-chart")
    ->json() ?? [];

$this->hourlyChart = Http::timeout(5)
    ->get("{$this->analyticsUrl}/analytics/hourly-chart")
    ->json() ?? [];

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