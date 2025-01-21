<?php

namespace App\Filament\Resources\StockHistoryResource\Widgets;

use App\Models\StockHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopProductChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Most Sold Products';

    protected function getData(): array
    {
        $topSoldProducts = StockHistory::query()
            ->with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('movement', 'remove')
            ->whereYear('created_at', '2025')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Top Sold Products',
                    'data' => $topSoldProducts->map(fn($product) => round($product->total_sold, 2)),
                ],
            ],
            'labels' => $topSoldProducts->map(fn($product) => $product->product?->name),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
