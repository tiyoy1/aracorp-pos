<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Product Name'),

                TextEntry::make('category')
                    ->label('Category')
                    ->color('primary'),

                TextEntry::make('cost_price')
                    ->label('Cost Price')
                    ->money('IDR')
                    ->color('gray'),

                TextEntry::make('price')
                    ->label('Selling Price')
                    ->money('IDR'),

                TextEntry::make('margin')
                    ->label('Profit Margin')
                    ->state(function ($record) {
                        if ($record->price <= 0) return '-';
                        $margin = (($record->price - $record->cost_price) / $record->price) * 100;
                        return number_format($margin, 1) . '%';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->price <= 0) return 'gray';
                        $margin = (($record->price - $record->cost_price) / $record->price) * 100;
                        return match(true) {
                            $margin >= 30 => 'success',
                            $margin >= 15 => 'warning',
                            default       => 'danger',
                        };
                    }),

                TextEntry::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->badge()
                    ->color(fn(int $state): string => match(true) {
                        $state <= 3  => 'danger',
                        $state <= 10 => 'warning',
                        default      => 'success',
                    }),

                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
            ]);
    }
}