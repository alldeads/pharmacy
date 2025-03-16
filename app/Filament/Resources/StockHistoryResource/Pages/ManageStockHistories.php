<?php

namespace App\Filament\Resources\StockHistoryResource\Pages;

use App\Filament\Resources\StockHistoryResource;
use App\Notifications\StockNotification;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Models\Stock;

class ManageStockHistories extends ManageRecords
{
    protected static string $resource = StockHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function ($record) {

                    $stock = Stock::where([
                        'product_id' => $record->product_id,
                        'branch_id' => $record->branch_id
                    ])->first();

                    $quantity = $record->movement == 'add' ? abs($record->quantity) : -1 * abs($record->quantity);

                    if ($stock) {

                        $stock->quantity += $quantity;
                        $stock->save();

                        if ($stock->quantity <= $stock->threshold) {
                            auth()->user()->notify(new StockNotification($stock));
                        }
                    } else {
                        $stock = Stock::create([
                            'product_id' => $record->product_id,
                            'branch_id' => $record->branch_id,
                            'quantity' => $quantity,
                            'threshold' => 50
                        ]);
                    }
                })
                ->slideOver(),
        ];
    }
}
