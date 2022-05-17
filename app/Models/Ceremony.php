<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ceremony extends Model
{
    use HasFactory;

    public $identifiableAttribute = 'scheduled_datetime';
    public $fillable = ['scheduled_datetime'];

    public function attendees ()
    {
        return $this->hasMany(Attendee::class);
    }

    public function recipients()
    {
        return $this->hasManyThrough('App\Models\Recipient', 'App\Models\Attendee', 'attendable_id', 'ceremonies_id')
        ->where(
            'attendable_type',
            'App\Models\Recipient'
        );
    }

    public function guests()
    {
        return $this->hasManyThrough(Guest::class, Attendee::class);
    }

    public function locationAddress() {
        return $this->belongsTo(Address::class);
    }

    // include attendees
    public function scopeAttendance($query)
    {
      return $query
      ->select('ceremonies.*',
          DB::raw("(
            SELECT count(*) FROM attendees
            WHERE attendees.ceremonies_id = ceremonies.id
          ) as total_attendees"));
    }

}
