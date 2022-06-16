<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [ 'status', 'ceremonies_id' ];

    /**
      * Get the parent attendable model (recipient, guest, vip).
    */
    public function attendable()
    {
        return $this->morphTo();
    }

    /**
      * Ceremony attachments
    */
    public function ceremonies()
    {
        return $this->belongsTo(Ceremony::class);
    }

    /**
      * Accommodation attachments
    */
    public function accommodations() {
      return $this->belongsToMany(Accommodation::class);
    }

    // filter recipients and include referenced data
    public function scopeRecipients($query)
    {
      return $query
      ->leftJoin('guests', function($join) {
        $join->on('guests.recipient_id', '=', 'attendees.attendable_id');
      })
      ->select(
        'attendees.*',
        'guests.id AS guest'
      )
      ->groupBy('attendees.attendable_id')
      ->where('attendees.attendable_type', 'App\Models\Recipient');
    }

    // include guests
    public function scopeGuests($query)
    {
      return $query
      ->leftJoin('guests', 'guests.id', '=', 'attendees.attendable_id')
      ->select(
        'attendees.*',
        'guests.first_name AS first_name',
        'guests.last_name AS last_name',
        )
      ->where('attendees.attendable_type', 'App\Models\Guest');
    }

}
