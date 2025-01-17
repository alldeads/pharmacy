<?php

namespace App\Filament\Resources\StockHistoryResource\Widgets;

use App\Models\StockHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StockHistoryChart extends ChartWidget
{
    protected static ?string $heading = 'Stocks';

    protected function getData(): array
    {
        $averageMovements = StockHistory::query()
            ->selectRaw('MONTH(created_at) as month,
                 AVG(CASE WHEN movement = "add" THEN quantity ELSE 0 END) as average_add,
                 AVG(CASE WHEN movement = "remove" THEN quantity ELSE 0 END) as average_minus')
            ->whereYear('created_at', '2025') // Replace '2025' with your desired year dynamically if needed
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $chartData = [
            'datasets' => [
                [
                    'label' => 'Average Add',
                    'data' => array_fill(0, 12, 0), // Initialize array with 12 zeros
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'Average Minus',
                    'data' => array_fill(0, 12, 0), // Initialize array with 12 zeros
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];

        // Map query results into the data arrays
        foreach ($averageMovements as $movement) {
            $index = $movement->month - 1; // Convert month to zero-based index
            $chartData['datasets'][0]['data'][$index] = round($movement->average_add, 2);
            $chartData['datasets'][1]['data'][$index] = round($movement->average_minus, 2);
        }

        return $chartData;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
