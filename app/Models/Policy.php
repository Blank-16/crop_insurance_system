<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = ['farmer_id', 'plan_id', 'status', 'start_date', 'end_date'];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function plan()
    {
        return $this->belongsTo(InsurancePlan::class, 'plan_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
