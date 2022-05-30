<?php
namespace App\Classes;
use App\Models\Recipient;
use App\Models\Ceremony;
use App\Models\Attendee;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecipientNoCeremonyRegistrationConfirm;
use App\Mail\RecipientRegistrationConfirm;
use App\Mail\SupervisorRegistrationConfirm;
use App\Mail\RecipientRegistrationReminder;
use App\Mail\RecipientCeremonyInvitation;
use App\Mail\RecipientCeremonyInvitationConfirm;
use DateTime;

use Illuminate\Support\Facades\Log;
class MailHelper
{

  /**
  * Select preferred email for recipient
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  private function getPreferredEmail(Recipient $recipient) {

    // check email address preference
    if (!empty($recipient->personal_email) && $recipient->preferred_email === 'personal') {
      return $recipient->personal_email;
    }
    else {
      return $recipient->government_email;
    }
  }

  /**
  * Send confirmation emails for ceremony sign-up
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @param string $email
  * @return \Illuminate\Http\Response
  */
  public function sendConfirmation(Recipient $recipient, string $email=null) {
    $email = empty($email) ? self::getPreferredEmail($recipient) : $email;
    if ($recipient->ceremony_opt_out == true) {
      Mail::to($email)->send(new RecipientNoCeremonyRegistrationConfirm($recipient));
    } else {
      Mail::to($email)->send(new RecipientRegistrationConfirm($recipient));
    }
    Mail::to($email)->send(new SupervisorRegistrationConfirm($recipient));
  }

  /**
  * Send registration reminder emails for ceremony sign-up
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @param string $email
  * @return \Illuminate\Http\Response
  */
  public function sendRegistrationReminder(Recipient $recipient, string $email=null) {
    $email = empty($email) ? self::getPreferredEmail($recipient) : $email;
    Mail::to($email)->queue(new RecipientRegistrationReminder($recipient));
  }

  /**
  * Send ceremony invitation to recipient
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @param \App\Model\Attendee $attendee
  * @param string $token
  * @param DateTime $expiry
  * @param string $email
  * @return \Illuminate\Http\Response
  */
  public function sendInvitation(
    Recipient $recipient,
    Ceremony $ceremony,
    Attendee $attendee,
    string $token,
    DateTime $expiry,
    string $email=null
  ) {
    $email = empty($email) ? self::getPreferredEmail($recipient) : $email;
    Mail::to($email)->queue(
      new RecipientCeremonyInvitation($recipient, $ceremony, $attendee, $token, $expiry)
    );
  }

  /**
  * Send ceremony RSVP confirmation to recipient
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @param \App\Model\Attendee $attendee
  * @param string $email
  * @return \Illuminate\Http\Response
  */
  public function sendRSVPConfirmation(Recipient $recipient, Attendee $attendee, string $email=null) {
    $email = empty($email) ? self::getPreferredEmail($recipient) : $email;
    Mail::to($email)->queue(
      new RecipientCeremonyInvitationConfirm($recipient, $attendee)
    );
  }

}
