<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('invoice_number')
                    ->label('PO Number')
                    ->required()
                    ->default(fn() => 'PO-' . now()->format('YmdHis'))
                    ->maxLength(255),

                Select::make('status')
                    ->options([
                        'draft'    => 'Draft',
                        'received' => 'Received',
                    ])
                    ->default('draft')
                    ->required()
                    ->helperText('Changing to "Received" will automatically add stock and update product cost.'),

                Repeater::make('items')
                    ->relationship('items')
                    ->label('Purchase Items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(1),

                        TextInput::make('cost_price')
                            ->label('Cost per Unit')
                            ->numeric()
                            ->required()
                            ->prefix('Rp'),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->addActionLabel('Add Item')
                    ->reorderable(false),
            ]);
    }
}