<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerProfile extends Model
{
    protected $fillable = ['user_id', 'land_size', 'crop_type', 'region'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
