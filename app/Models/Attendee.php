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

     /**** RSVP form functions *****/

    /**
     * Get recipient record with additional fields.
     *  Get recipient, ceremony and attendee status based on id from query string.
     * @param int $id: ID from the query string.
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getByRecipientId(int $id)
    {
        $result = DB::table('recipients')
            ->leftjoin('attendees', 'recipients.id', '=', 'attendees.recipient_id')
            ->leftjoin('ceremonies', 'recipients.ceremony_id', '=', 'ceremonies.id')
            ->select('recipients.first_name', 'recipients.last_name', 'attendees.*', 'ceremonies.scheduled_datetime')
            ->where('attendees.id', '=', $id)
            ->first();
        return ($result);
    }

}
