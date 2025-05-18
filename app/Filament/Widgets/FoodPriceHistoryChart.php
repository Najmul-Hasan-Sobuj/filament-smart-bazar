<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class FoodPriceHistoryChart extends ChartWidget
{
    public ?Food $record = null;

    protected static ?string $heading = 'মূল্য ইতিহাস';

    protected function getData(): array
    {
        if (!$this->record) {
            return [];
        }

        $data = $this->record->priceHistories()
            ->orderBy('changed_at')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'মূল্য',
                    'data' => $data->map(fn ($item) => $item->new_price),
                    'borderColor' => '#10B981',
                ],
            ],
            'labels' => $data->map(fn ($item) => Carbon::parse($item->changed_at)->format('d M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
} 