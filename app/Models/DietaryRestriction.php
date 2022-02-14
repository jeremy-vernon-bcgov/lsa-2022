<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietaryRestriction extends Model
{
    use HasFactory;

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class);
    }




}
