<?php
namespace App\Classes;
use App\Models\Attendee;
use Illuminate\Support\Facades\Log;
class AttendeesHelper
{


  /**
  * Get ceremony from list of attendees
  *
  * @return Array
  */

  private function getAttendanceByCeremonyID($attendee, $ceremony_id) {
    $attendances = $attendee->attendee()->get()->toArray();
    return current(array_filter($attendances, function($attendance) {
      return isset($attendance['ceremonies_id']) && $ceremony_id == $attendance['ceremonies_id'];
    }));
  }

  /**
  * Create new attendee with assignment
  *
  * @return Array
  */

  private function create($status, $ceremony_id) {
    return new Attendee([
      'status' => $status,
      'ceremonies_id' => $ceremony_id
    ]);
  }

  /**
  * Update attendee status
  *
  * @return Array
  */

  private function update($attendee, $status, $ceremony_id) {
    $thisAttendance = getAttendanceByCeremonyID($attendee, $ceremony_id);
    $attendance = Attendee::where([
      'id' => $thisAttendance->id,
      'ceremonies_id' => $ceremony_id
    ])
    ->firstOrFail();
    $attendance->status = $status;
    $attendance->save();
  }

  /**
  * Assign attendance status to attendee
  * - Statuses: waitlisted | assigned | invited | attending | declined
  *
  * @return Array
  */
  public function assignStatus($attendee, $status, $ceremony_id) {

    // check for ceremony opt-out and registration declaration
    if ($attendee->ceremony_opt_out || !$attendee->is_declared) {
      return response()->json([
            'errors' => "Attendee cannot be assigned a ceremony",
        ], 500);
    }

    // apply rules of status assignment
    switch ($status) {

      case 'assigned':
        // clear all existing assignments
        $attendee->attendee()->delete();
        // assign attendee to ceremony
        $attendance = $this->create('assigned', $ceremony_id);
        $attendee->attendee()->save($attendance);
      break;

      case 'waitlisted':
        // get attendance record for requested ceremony
        $attendance = Attendee::where([
          'attendable_id' => $attendee->id,
          'ceremonies_id' => $ceremony_id
        ])->first();

        // waitlist attendee if not already set
        if ( empty($attendance) ) {
          $attendance = $this->create('waitlisted', $ceremony_id);
          $attendee->attendee()->save($attendance);
        }
      break;

      case 'invited':
        $attendance = $this->update($attendee, 'invited', $ceremony_id);
        $attendee->attendee()->save($attendance);
      break;

      case 'attending':
        $attendee = $this->update($attendee, 'attending', $ceremony_id);
        $attendee->attendee()->save($attendee);
      break;

      case 'declined':
        $attendee = $this->update($attendee, 'declined', $ceremony_id);
        $attendee->attendee()->save($attendee);
      break;

      case 'reset':
        $attendee->attendee()->delete();
      break;

      default:
      // code...
      break;
    }

    return $attendee;
  }
}
