<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsurancePlan extends Model
{
    protected $fillable = ['proposer_id', 'crop_type', 'region', 'premium', 'coverage', 'duration'];

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_id');
    }

    public function policies()
    {
        return $this->hasMany(Policy::class, 'plan_id');
    }
}
