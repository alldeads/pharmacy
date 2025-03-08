<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use Illuminate\Contracts\View\View;
use App\Models\Branch;
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
use Illuminate\Database\Eloquent\Model;

class ViewBranch extends ViewRecord
{
    protected static string $resource = BranchResource::class;

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
                        TextEntry::make('status')
                            ->formatStateUsing(function ($state) {
                                return ucfirst($state);
                            }),
                    ])
                        ->columns(2),
                    Section::make([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                        ->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('md'),
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('Stocks')
                            ->schema([
                                RepeatableEntry::make('stocks')
                                    ->hiddenLabel()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->hiddenLabel()
                                                     ->formatStateUsing(fn (string $state, Model $record): View => view(
                                                        'components.custom-href',
                                                        ['state' => $state, 'record' => $record->product_id, 'model' => 'products'],
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
                                                TextEntry::make('product.name')
                                                    ->hiddenLabel()
                                                    ->formatStateUsing(fn(string $state, Model $record): View => view(
                                                        'components.custom-href',
                                                        ['state' => $state, 'record' => $record->product_id, 'model' => 'products'],
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
