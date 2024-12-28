<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockHistoryResource\Pages;
use App\Filament\Resources\StockHistoryResource\RelationManagers;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockHistoryResource extends Resource
{
    protected static ?string $model = StockHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Stocks';

    protected static ?string $label = 'Movement';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->placeholder('Select product')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('branch_id')
                    ->label('Branch')
                    ->placeholder('Select branch')
                    ->options(Branch::pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),
                Forms\Components\Select::make('movement')
                    ->options(['remove' => 'Remove', 'add' => 'Add'])
                    ->columnSpanFull(),
                TextArea::make('description')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('movement')
                    ->icon(fn (string $state): string => match ($state) {
                        'add' => 'heroicon-o-arrow-trending-up',
                        'remove' => 'heroicon-o-arrow-trending-down',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'add' => 'success',
                        'remove' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStockHistories::route('/'),
        ];
    }
}
