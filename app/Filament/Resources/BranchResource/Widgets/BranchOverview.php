<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Stock;
use App\Notifications\ProductExpiredNotification;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BranchOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $productsExpiringSoon = Product::whereBetween('expired_at', [
            Carbon::now(),
            Carbon::now()->addDays(7),
        ])->get();

        foreach ($productsExpiringSoon as $product) {
            auth()->user()->notify(new ProductExpiredNotification($product));
        }

        return [
            Stat::make('Branches', Branch::count()),
            Stat::make('Categories', Category::count()),
            Stat::make('Products', Product::count()),
            Stat::make('Suppliers', Supplier::count()),
            Stat::make('Stocks', Stock::count()),
        ];
    }
}
