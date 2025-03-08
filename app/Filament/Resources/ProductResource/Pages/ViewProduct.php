<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('sku'),
                        TextEntry::make('price'),
                        TextEntry::make('category.name'),
                        TextEntry::make('status')
                            ->formatStateUsing(function ($state) {
                                return ucfirst($state);
                            }),
                        TextEntry::make('id')
                            ->label('Quantity')
                            ->formatStateUsing(function ($record) {
                                return $record->stocks()->sum('quantity');
                            }),
                        TextEntry::make('description'),
                    ])
                        ->columns(2),
                    Section::make([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                        TextEntry::make('expired_at')
                            ->dateTime(),
                    ])
                        ->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('md'),
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('Branches')
                            ->schema([
                                RepeatableEntry::make('stocks')
                                    ->hiddenLabel()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('branch.name')
                                                    ->hiddenLabel()
                                                     ->formatStateUsing(fn (string $state, Model $record): View => view(
                                                        'components.custom-href',
                                                        ['state' => $state, 'record' => $record->branch_id, 'model' => 'branches'],
                                                    )),
                                                TextEntry::make('quantity')
                                                    ->hiddenLabel()
                                            ])
                                    ])
                            ]),
                        Tabs\Tab::make('History')
                            ->schema([
                                RepeatableEntry::make('stockHistory')
                                    ->hiddenLabel()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextEntry::make('branch.name')
                                                    ->hiddenLabel()
                                                    ->formatStateUsing(fn(string $state, Model $record): View => view(
                                                        'components.custom-href',
                                                        ['state' => $state, 'record' => $record->branch_id, 'model' => 'branches'],
                                                    )),
                                                TextEntry::make('quantity')
                                                    ->hiddenLabel(),
                                                TextEntry::make('movement')
                                                    ->hiddenLabel()
                                                    ->formatStateUsing(function ($state) {
                                                        return ucfirst($state);
                                                    }),
                                            ])
                                    ])
                            ]),
                    ])
            ]);
    }
}
