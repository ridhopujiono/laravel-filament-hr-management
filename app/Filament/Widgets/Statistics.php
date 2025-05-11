<?php

namespace App\Filament\Widgets;

use App\Models\Leave;
use App\Models\LeaveApproval;
use App\Models\LeaveType;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class Statistics extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }
    protected function getStats(): array
    {
        $employeeCount = User::count();
        return [
            Stat::make('Employee Total', $employeeCount),
            Stat::make('Leaves This Month', Leave::whereMonth('start_date', now()->month)->count()),
            Stat::make('Need Approval', LeaveApproval::where('status', 'pending')->count()),
        ];
    }
}
