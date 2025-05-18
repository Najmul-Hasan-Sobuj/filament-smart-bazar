<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        return [
            Stat::make('মোট ব্যবহারকারী', $totalUsers)
                ->description('সকল ব্যবহারকারীর সংখ্যা')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('যাচাইকৃত ব্যবহারকারী', $verifiedUsers)
                ->description('ইমেইল যাচাইকৃত ব্যবহারকারী')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),
            Stat::make('অযাচাইকৃত ব্যবহারকারী', $unverifiedUsers)
                ->description('ইমেইল অযাচাইকৃত ব্যবহারকারী')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
} 