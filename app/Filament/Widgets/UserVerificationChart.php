<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserVerificationChart extends ChartWidget
{
    protected static ?string $heading = 'ব্যবহারকারী যাচাইকরণের অবস্থা';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        return [
            'datasets' => [
                [
                    'label' => 'ব্যবহারকারী',
                    'data' => [$verifiedUsers, $unverifiedUsers],
                    'backgroundColor' => ['#10B981', '#EF4444'],
                    'borderColor' => ['#059669', '#DC2626'],
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['যাচাইকৃত', 'অযাচাইকৃত'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
        ];
    }
} 