<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FoodPriceChart;
use App\Filament\Widgets\FoodStatsWidget;
use App\Filament\Widgets\PriceChangeChart;
use App\Filament\Widgets\PriceChangeStatsWidget;
use App\Filament\Widgets\UserStatsWidget;
use App\Filament\Widgets\UserVerificationChart;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'ড্যাশবোর্ড';
    protected static ?string $title = 'ড্যাশবোর্ড';
    protected static ?int $navigationSort = -2;

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            FoodStatsWidget::class,
            PriceChangeStatsWidget::class,
            UserStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            FoodPriceChart::class,
            PriceChangeChart::class,
            UserVerificationChart::class,
        ];
    }
} 