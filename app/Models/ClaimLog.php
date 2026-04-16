<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimLog extends Model
{
    protected $fillable = [
        'claim_id',
        'status',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
