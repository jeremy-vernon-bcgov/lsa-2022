<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    public function recipient ()
    {
        return $this->belongsTo(Recipient::class);
    }
    public function attendee()
    {
        return $this->hasOne(Attendee::class);
    }
    public function ceremonies ()
    {
        return $this->hasManyThrough(Ceremony::class, Attendee::class);
    }
    public function dietaryRestrictions ()
    {
        return $this->hasManyThrough(DietaryRestriction::class, Attendee::class);
    }
    public function accessibilityOptions ()
    {
        return $this->hasManyThrough(AccessibilityOption::class, Attendee::class);
    }

}
