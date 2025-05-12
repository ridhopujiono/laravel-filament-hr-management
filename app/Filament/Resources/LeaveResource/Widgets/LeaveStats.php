<?php

namespace App\Filament\Resources\LeaveResource\Widgets;

use App\Models\Leave;
use App\Models\LeaveType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class LeaveStats extends BaseWidget
{
    public static function canView(): bool
    {
        return !Auth::user()->hasRole('super_admin');
    }

    protected function getStats(): array
    {
        $leaveTypes = LeaveType::all();
        $stats = [];
        foreach ($leaveTypes as $leaveType) {
            $data = Leave::where('leave_type_id', $leaveType->id)->where('user_id', Auth::user()->id)
            ->whereHas('leaveApproval', function ($query) {
                $query->where('status', 'approved');
            })
            ->count();
            $maxDays = $leaveType->max_days;
            $stats[] = Stat::make($leaveType->name, $maxDays - $data . ' day(s) remaining');
        }
        return $stats;
    }
}
