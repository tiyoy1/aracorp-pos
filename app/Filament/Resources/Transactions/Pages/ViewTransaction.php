<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\ReturnItem;
use App\Models\ReturnTransaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('processReturn')
                ->label('Process Return')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->visible(fn () => $this->record->transactionItems
                    ->contains(fn ($item) => $item->returnableQuantity() > 0))
                ->modalHeading('Process Return')
                ->modalDescription('Select items and quantities to return. Stock is restored automatically.')
                ->form([
                    Repeater::make('items')
                        ->label('Items To Return')
                        ->schema([
                            Select::make('transaction_item_id')
                                ->label('Item')
                                ->options(fn () => $this->record->transactionItems
                                    ->filter(fn ($item) => $item->returnableQuantity() > 0)
                                    ->mapWithKeys(fn ($item) => [
                                        $item->id => "{$item->product->name} ({$item->returnableQuantity()} of {$item->quantity} returnable)",
                                    ]))
                                ->distinct()
                                ->required(),

                            TextInput::make('quantity')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->addActionLabel('Add Another Item'),

                    Textarea::make('reason')
                        ->label('Reason (optional)')
                        ->rows(2),
                ])
                ->action(function (array $data): void {
    try {
        DB::transaction(function () use ($data): void {
            $returnTransaction = ReturnTransaction::create([
                'transaction_id' => $this->record->id,
                'reason'         => $data['reason'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                ReturnItem::create([
                    'return_transaction_id' => $returnTransaction->id,
                    'transaction_item_id'   => $item['transaction_item_id'],
                    'quantity'               => $item['quantity'],
                ]);
            }
        });

        $this->record->refresh(); // ← reload the record so the infolist sees the new return

        Notification::make()
            ->title('Return processed successfully')
            ->success()
            ->send();
    } catch (\RuntimeException $e) {
        Notification::make()
            ->title('Return failed')
            ->body($e->getMessage())
            ->danger()
            ->send();
                    }
                }),

            EditAction::make(),
        ];
    }
}