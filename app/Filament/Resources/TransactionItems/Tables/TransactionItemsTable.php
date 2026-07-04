<?php

namespace App\Filament\Resources\TransactionItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Show invoice number instead of raw ID
                TextColumn::make('transaction.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->weight('semibold')
                    ->color('primary'),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Unit Price')
                    ->money('IDR')  // ← fix $ to Rp
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')  // ← fix $ to Rp
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}