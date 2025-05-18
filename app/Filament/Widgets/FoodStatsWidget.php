<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FoodStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalFoods = Food::count();
        $avgPrice = Food::avg('price');
        $highestPrice = Food::max('price');
        $lowestPrice = Food::min('price');

        return [
            Stat::make('মোট খাদ্য সামগ্রী', $totalFoods)
                ->description('সকল খাদ্য সামগ্রীর সংখ্যা')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),
            Stat::make('গড় মূল্য', number_format($avgPrice, 2) . ' টাকা')
                ->description('সকল খাদ্য সামগ্রীর গড় মূল্য')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('সর্বোচ্চ মূল্য', number_format($highestPrice, 2) . ' টাকা')
                ->description('সর্বোচ্চ মূল্যের খাদ্য সামগ্রী')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('সর্বনিম্ন মূল্য', number_format($lowestPrice, 2) . ' টাকা')
                ->description('সর্বনিম্ন মূল্যের খাদ্য সামগ্রী')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('info'),
        ];
    }
} 