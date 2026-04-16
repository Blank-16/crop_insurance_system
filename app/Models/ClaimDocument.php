<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    protected $fillable = ['claim_id', 'file_path'];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
