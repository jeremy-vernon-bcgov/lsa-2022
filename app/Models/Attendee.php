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

    // include recipients
    public function scopeRecipients($query)
    {
      return $query
      ->leftJoin('recipients', 'recipients.id', '=', 'attendees.attendable_id')
      ->leftJoin('guests', 'guests.recipient_id', '=', 'recipients.id')
      ->select(
        'attendees.*',
        'recipients.id AS recipient_id',
        'recipients.employee_number AS employee_number',
        'recipients.first_name AS first_name',
        'recipients.last_name AS last_name',
        'guests.id AS guest',
        )
      ->groupBy('recipients.id')
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
