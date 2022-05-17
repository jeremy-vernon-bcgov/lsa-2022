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
  * @return \Illuminate\Http\Response
  */
  public function sendConfirmation(Recipient $recipient) {
    $email = self::getPreferredEmail($recipient);
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
  * @return \Illuminate\Http\Response
  */
  public function sendRegistrationReminder(Recipient $recipient) {
    $email = self::getPreferredEmail($recipient);
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
  * @return \Illuminate\Http\Response
  */
  public function sendInvitation(Recipient $recipient, Ceremony $ceremony, Attendee $attendee, string $token, DateTime $expiry) {
    $email = self::getPreferredEmail($recipient);
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
  * @return \Illuminate\Http\Response
  */
  public function sendRSVPConfirmation(Recipient $recipient, Attendee $attendee) {
    $email = self::getPreferredEmail($recipient);
    Mail::to($email)->queue(
      new RecipientCeremonyInvitationConfirm($recipient, $attendee)
    );
  }

}
