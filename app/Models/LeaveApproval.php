<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $fillable = [
        'leave_id',
        'approved_by',
        'status',
        'notes',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
