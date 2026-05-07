<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                Select::make('type')
                    ->options([
                        'in' => 'In',
                        'out' => 'Out',
                    ]),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('note')
                    ->required(),
            ]);
    }
}
