<?php

namespace App\Filament\Resources\TransactionItems\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function($state, callable $set) {
                        $product = Product::find($state);
                        $set('price', $product?->price ?? 0);
                    }),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('price')
                    ->dehydrated()
                    ->numeric()
                    ->disabled(),
            ]);
    }
}
