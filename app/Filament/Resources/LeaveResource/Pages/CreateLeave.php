<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\LeaveApproval;
use App\Models\LeaveType;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kalau bukan super_admin, tambahkan user_id dari Auth
        if (!Auth::user()->hasRole('super_admin')) {
            $data['user_id'] = Auth::id();
        }

        return $data;
    }

    protected function beforeCreate(): void
    {
        // $email = $this->data['email'];

        // if (\App\Models\User::where('email', $email)->where('role', 'teacher')->exists()) {
        //     Notification::make()
        //         ->title('Email sudah digunakan oleh guru.')
        //         ->danger()
        //         ->send();

        //     // batalkan proses insert
        //     $this->halt(); // atau gunakan throw
        // }
        $leaveType = LeaveType::find($this->data['leave_type_id']);
        $remainingDays = $leaveType->max_days - Leave::where('leave_type_id', $this->data['leave_type_id'])->where('user_id', Auth::user()->id)->whereHas('leaveApproval', function ($query) {
            $query->where('status', 'approved');
        })->count();

        if ($remainingDays < abs(Date::parse($this->data['end_date'])->diffInDays($this->data['start_date']))) {
            Notification::make()
                ->title('Jumlah hari melebihi batas maksimum')
                ->danger()
                ->send();

            // batalkan proses insert
            $this->halt(); // atau gunakan throw
        }
    }

    // after create
    protected function afterCreate(): void
    {
        LeaveApproval::create([
            'leave_id' => $this->record->id,
            'status' => 'pending',
        ]);
    }
}
