<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'policy_id', 'status', 'description', 
        'damage_type', 'damage_percentage', 'calculated_amount', 'remarks',
        'verification_flag', 'verification_reason'
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function documents()
    {
        return $this->hasMany(ClaimDocument::class);
    }

    public function logs()
    {
        return $this->hasMany(ClaimLog::class);
    }
}
