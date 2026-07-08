<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;
use App\Models\TransactionItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\ReturnTransaction;
use App\Models\ReturnItem;
use App\Observers\PurchaseOrderObserver;
use App\Observers\PurchaseOrderItemObserver;
use App\Observers\ReturnTransactionObserver;
use App\Observers\ReturnItemObserver;
use App\Observers\StockMovementObserver;
use App\Observers\TransactionItemObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        TransactionItem::observe(TransactionItemObserver::class);
        StockMovement::observe(StockMovementObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        PurchaseOrderItem::observe(PurchaseOrderItemObserver::class);
        ReturnTransaction::observe(ReturnTransactionObserver::class);
        ReturnItem::observe(ReturnItemObserver::class);
    }
    

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
