<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\LeaveApproval;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    // after create
    protected function afterCreate(): void
    {
        LeaveApproval::create([
            'leave_id' => $this->record->id,
            'status' => 'pending',
        ]);
    }
}
