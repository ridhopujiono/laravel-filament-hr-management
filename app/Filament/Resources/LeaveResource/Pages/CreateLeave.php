<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\LeaveApproval;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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
    // after create
    protected function afterCreate(): void
    {
        LeaveApproval::create([
            'leave_id' => $this->record->id,
            'status' => 'pending',
        ]);
    }
}
