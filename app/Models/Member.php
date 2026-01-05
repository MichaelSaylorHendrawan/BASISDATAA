<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'branch_id',
        'tier_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'join_date',
        'status',
    ];

    // Relasi optional
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'tier_id');
    }

    // helper accessor full name
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}