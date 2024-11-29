<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Generic;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BranchOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Branches', Branch::count()),
            Stat::make('Categories', Category::count()),
            Stat::make('Products', Product::count()),
            Stat::make('Generics', Generic::count()),
        ];
    }
}
