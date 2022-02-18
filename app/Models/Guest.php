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
        return $this->morphOne(Attendee::class, 'attendable');
    }
    public function ceremonies ()
    {
        return $this->hasManyThrough(Ceremony::class, Attendee::class);
    }

    public function accommodations()
    {
        return $this->hasManyThrough(Accommodation::class, Attendee::class);
    }


}
