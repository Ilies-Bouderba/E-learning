<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $fillable = ['user_id', 'status', 'message', 'resolved_at'];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
}
