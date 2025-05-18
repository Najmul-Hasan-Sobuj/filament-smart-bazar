<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FoodPriceChart extends ChartWidget
{
    protected static ?string $heading = 'খাদ্য সামগ্রীর মূল্য বিশ্লেষণ';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $foods = Food::select('name', 'price')
            ->orderBy('price', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'মূল্য (টাকা)',
                    'data' => $foods->pluck('price')->toArray(),
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#059669',
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                    'hoverBackgroundColor' => '#059669',
                ],
            ],
            'labels' => $foods->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value + " টাকা"; }',
                    ],
                ],
            ],
        ];
    }
} 