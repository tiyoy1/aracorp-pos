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

                TextEntry::make('price')
                    ->label('Price')
                    ->money('IDR'),

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