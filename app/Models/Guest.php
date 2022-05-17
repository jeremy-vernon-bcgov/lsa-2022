<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    public $fillable = ['first_name', 'last_name', 'recipient_id'];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function attendee() {
        return $this->morphMany(Attendee::class, 'attendable')->with('ceremonies');
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
