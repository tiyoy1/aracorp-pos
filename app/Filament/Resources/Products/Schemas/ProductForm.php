<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Category')
                    ->options([
                        'Electronics'  => 'Electronics',
                        'Food'         => 'Food',
                        'Beverages'    => 'Beverages',
                        'Stationery'   => 'Stationery',
                        'Clothing'     => 'Clothing',
                        'Other'        => 'Other',
                    ])
                    ->searchable()
                    ->required(),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('stock')
                    ->required()
                    ->numeric(),
            ]);
    }
}