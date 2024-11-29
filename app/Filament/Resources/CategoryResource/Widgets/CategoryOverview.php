<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CategoryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Categories', Category::count()),
        ];
    }
}
