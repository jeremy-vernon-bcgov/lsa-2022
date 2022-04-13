<?php
namespace App\Classes;
use Illuminate\Support\Facades\Log;
class AttendeesHelper
{

  /**
  * Get status of attendee
  *
  * @return Array
  */

  private function isWaitlisted($attendee)
  {
    return $attendee.status === 'waitlisted';
  }

  private function isAssigned($attendee)
  {
    return $attendee.status === 'assigned';
  }

  private function isInvited($attendee)
  {
    return $attendee.status === 'invited';
  }

  private function isAttending($attendee)
  {
    return $attendee.status === 'attending';
  }

  private function isDeclined($attendee)
  {
    return $attendee.status === 'declined';
  }

  /**
  * Get ceremony from list of attendees
  *
  * @return Array
  */

  private function getByCeremony($attendees, $ceremony) {
    return current(array_filter($attendees, function($attendee) {
      return isset($attendee['ceremonies_id']) && $ceremony == $attendee['ceremonies_id'];
    }));
  }

  /**
  * Create new attendee with assignment
  *
  * @return Array
  */

  private function createAssignment($status, $ceremony) {
    return new Attendee([
      'status' => $status,
      'ceremonies_id' => $ceremony
    ]);
  }


  /**
  * Handle attendee assignment: 'assigned'
  * - attendee at ceremony can be one of:
  * -- 1-1. has status other than assigned or waitlisted [skip]
  * -- 1-2. not assigned to any and not waitlisted for same [assignment]
  * -- 1-3. not assigned to any and waitlisted for same [reassignment]
  * -- 1-4. assigned to other and waitlisted for same [reassignment]
  * -- 1-5. assigned to other and not waitlisted for same [reassignment]
  * -- 1-6. waitlisted for ceremony [reassignment]
  * -- 1-7. assigned to ceremony [skip]
  *
  * @return Array
  */

  private function setAssigned($recipient, $ceremony) {
    // subcase 1-1 (not assigned, not waitlisted)
    if (empty($assigned) && empty($waitlistedForCeremony)) {

    }
    // subcase 1-2 (assigned, not waitlisted)
    elseif ($assigned->ceremonies_id !== $ceremony && empty($waitlistedForCeremony)) {
      $attendee = createAssignment($status, $ceremony);
      $recipient->attendee()->save($attendee);
    }
    // subcase 1-3
    elseif (!empty($waitlistedForCeremony)) {

      if ($assigned->ceremonies_id !== $ceremony) {

        $assigned->status = 'waitlisted';
        $assigned->save();
        $attendee = createAssignment($status, $ceremony);
        if ($waitlistedForCeremony) {
          $waitlistedForCeremony
        }
      }
      elseif (!empty()) {
        // code...
      }
      else {

      }
    }
  }

  /**
  * Handle attendee assignment: 'waitlisted'
  *
  * @return Array
  */

  private function setWaitlisted($recipient, $ceremony) {
    return

  }

  /**
  * Assign attendee status to recipient
  * - Status: waitlisted | assigned | invited | attending | declined
  * CASE 1: Assigned
  *
  * @return Array
  */
  public function assignStatus($recipient, $status, $ceremony) {

    $assignments = $recipient->attendee()->get()->toArray();
    $assigned = current(array_filter($assignments, "isAssigned"));
    $waitlisted = array_filter($assignments, "isWaitlisted");

    // get attendee for requested ceremony
    $attendee = current($this.getByCeremony($assignments, $ceremony));

    Log::info('Assignments', array(
      'status' => $status,
      'ceremony' => $ceremony,
      'waitlisted' => $waitlisted,
      'assigned' => $assigned,
      'attendance' => $attendee
    ));

    // apply rules of status assignment
    switch ($status) {

      case 'assigned':
        $this.setAssigned($recipient, $ceremony);
      break;

      case 'waitlisted':
        $this.setWaitlisted($recipient, $ceremony);
      break;

      default:
      // code...
      break;
    }
    return $recipient;
  }
}
