<?php
namespace App\Classes;
use App\Models\Attendee;
use App\Models\Ceremony;
use App\Classes\MailHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
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
    return current(array_filter($attendances, function($attendance) use($ceremony_id) {
      return isset($attendance['ceremonies_id']) && $ceremony_id == $attendance['ceremonies_id'];
    }));
  }

  /**
  * Get assigned ceremony for attendee
  *
  * @return Array
  */

  public function getAssignedCeremony($attendee) {
    $attendances = $attendee->attendee()->get()->toArray();
    return current(array_filter($attendances, function($attendance) {
      return isset($attendance['status']) && 'assigned' == $attendance['status'];
    }));
  }

  /**
  * Get top level status for attendee
  *
  * @return Array
  */

  public function getStatus($attendee) {
    $attendances = $attendee->attendee()->get()->toArray();
    $status = '';
    return current(array_filter($attendances, function($attendance) {
      if (empty($status) || $attendance['status'] !== 'waitlisted') {
        $status = $attendance['status'];
      }
    }));
    return $status;
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
    $thisAttendance = $this->getAttendanceByCeremonyID($attendee, $ceremony_id);
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
        // check attendee status
        $status = $this->getStatus($attendee);

        // check that attendee can waitlist
        if ($status === 'invited' || $status === 'declined' || $status === 'attending') {
          return response()->json([
                'errors' => "Attendee cannot be waitlisted for this ceremony",
            ], 500);
        }

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
        // get assigned ceremony
        $ceremonyData = $this->getAssignedCeremony($attendee);
        $ceremony = Ceremony::find($ceremonyData['ceremonies_id']);

        // clear all existing assignments
        $attendee->attendee()->delete();

        Log::info('Invitation', array(
          'id' => $attendee->id,
          'class' => class_basename($attendee),
        ));

        // assign attendee as invited to ceremony
        $attendance = $this->create('invited', $ceremonyData['ceremonies_id']);
        $attendee->attendee()->save($attendance);

        // generate and store RSVP access token
        $token = Str::random(60);
        $key = Hash::make(class_basename($attendee) . $attendee->id);
        Cache::put($key, $token);

        // send invitation email
        $mailer = new MailHelper();
        $mailer->sendInvitation($attendee, $ceremony, $key, $token);

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
