<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PriceChangeStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalChanges = DB::table('food_price_histories')->count();
        $todayChanges = DB::table('food_price_histories')
            ->whereDate('created_at', today())
            ->count();
        $avgPriceChange = DB::table('food_price_histories')
            ->select(DB::raw('AVG(ABS(new_price - old_price)) as avg_change'))
            ->first()
            ->avg_change;

        return [
            Stat::make('মোট মূল্য পরিবর্তন', $totalChanges)
                ->description('সকল মূল্য পরিবর্তনের সংখ্যা')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('আজকের পরিবর্তন', $todayChanges)
                ->description('আজকের মূল্য পরিবর্তনের সংখ্যা')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
            Stat::make('গড় মূল্য পরিবর্তন', number_format($avgPriceChange, 2) . ' টাকা')
                ->description('গড় মূল্য পরিবর্তনের পরিমাণ')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
} 