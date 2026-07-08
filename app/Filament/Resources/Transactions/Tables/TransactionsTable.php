<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->weight('semibold')
                    ->color('primary'),

                TextColumn::make('cashier.name')
                    ->label('Cashier')
                    ->searchable()
                    ->placeholder('—')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime('d M Y')
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