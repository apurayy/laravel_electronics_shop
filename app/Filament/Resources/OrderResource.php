<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Number;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->required()
                    ->label('User')
                    ->relationship('user', 'name')
                    ->preload()
                    ->searchable(),


                Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'Cod' => 'Cash on Delivery',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->default('credit_card')
                    ->required(),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),

                ToggleButtons::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->inline()
                    ->default('pending')
                    ->required()
                    ->label('Order Status')
                    ->colors([
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ]),


                Select::make('currency')
                    ->options([
                        'BDT' => 'BDT',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                    ])
                    ->default('BDT')
                    ->required(),

                Select::make('shipping_method')
                    ->options([
                        'standard' => 'Standard Shipping',
                        'express' => 'Express Shipping',
                        'pickup' => 'Store Pickup',
                    ])
                    ->default('standard')
                    ->required(),

                Forms\Components\Textarea::make('order_notes')
                    ->columnSpanFull(),


                Section::make('Order Items')->schema([
                    Repeater::make('orderItems')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->required()
                                ->label('Product')
                                ->relationship('product', 'name')
                                ->preload()
                                ->searchable()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->columnSpan(4)
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, ?int $state) {
                                    $product = \App\Models\Product::find($state);
                                    $set('unit_amount', $product ? $product->price : 0);
                                    $set('total_amount', $product ? $product->price : 0);
                                }),

                            TextInput::make('quantity')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->label('Quantity')
                                ->columnSpan(2)
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, ?int $state, callable $get) {
                                    $unitPrice = $get('unit_amount') ?? 0;
                                    $set('total_amount', $unitPrice * $state);
                                }),

                            TextInput::make('unit_amount')
                                ->numeric()
                                ->required()
                                ->label('Unit Amount')
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                ->numeric()
                                ->required()
                                ->dehydrated()
                                ->label('Total Amount')
                                ->columnSpan(3),

                           Placeholder::make('total_price')
                                ->label('Total Price')
                                ->content(fn (callable $get) =>
                                    Number::currency(
                                        ($get('quantity') ?? 0) * ($get('unit_amount') ?? 0),
                                        'BDT'
                                    )
                                )




                        ])->columns(12),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),

                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('More actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
