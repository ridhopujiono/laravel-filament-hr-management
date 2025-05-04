<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'leave_type_id',
        'status',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo{
        return $this->belongsTo(LeaveType::class);
    }
}
