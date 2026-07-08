<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice_number')
                    ->label('Invoice'),

                TextEntry::make('cashier.name')
                    ->label('Cashier')
                    ->placeholder('—'),

                TextEntry::make('total_price')
                    ->label('Total')
                    ->money('IDR'),

                TextEntry::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),

                RepeatableEntry::make('transactionItems')
                    ->label('Items Sold')
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Product'),
                        TextEntry::make('quantity')
                            ->label('Qty Sold'),
                        TextEntry::make('price')
                            ->label('Unit Price')
                            ->money('IDR'),
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                RepeatableEntry::make('returnTransactions')
                    ->label('Returns')
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Return #'),
                        TextEntry::make('total_refund')
                            ->label('Refunded')
                            ->money('IDR'),
                        TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('reason')
                            ->label('Reason')
                            ->placeholder('—'),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->visible(fn ($record) => $record->returnTransactions->isNotEmpty()),
            ]);
    }
}