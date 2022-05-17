<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Recipient;
use App\Models\Ceremony;
use App\Models\Attendee;
use DateTime;
use DateTimeZone;

class   RecipientCeremonyInvitation extends Mailable
{
  use Queueable, SerializesModels;

  /**
  * The Registered Recipient
  *
  * @var \App\Models\Recipient;
  */
  public $recipient;

  /**
  * Ceremony
  *
  * @var \App\Models\Ceremony;
  */
  public $ceremony;

  /**
  * Attendee
  */
  public $attendee;

  /**
  * RSVP token
  */
  public $token;

  /**
  * RSVP token
  */
  public $expiry;

  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct(
    Recipient $recipient, Ceremony $ceremony, Attendee $attendee, string $token, DateTime $expiry)
  {
    $this->recipient = $recipient;
    $this->ceremony = $ceremony;
    $this->attendee = $attendee;
    $this->token = $token;
    $this->expiry = $expiry->format('g:ia T \o\n l jS F Y');
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    // build attendee RSVP URL
    $baseURL = env('FRONTEND_URL') . 'registration/rsvp';

    // send attendee ID as RSVP key
    $id = $this->attendee->id;
    $token = $this->token;
    $declinedURL = "$baseURL/declined/$id/$token";
    $attendingURL = "$baseURL/attending/$id/$token";

    // format scheduled ceremony date/time
    $scheduled_datetime = new DateTime($this->ceremony->scheduled_datetime);
    $scheduled_datetime->setTimezone(new DateTimeZone('America/Vancouver'));

    return $this
    ->subject('Invitation to Long Service Awards Ceremony')
    ->view('emails.recipientCeremonyInvitation', [
      'first_name' => $this->recipient->first_name,
      'last_name' => $this->recipient->last_name,
      'scheduled_datetime' => $scheduled_datetime->format('g:ia T \o\n l jS F Y'),
      'expiry' => $this->expiry,
      'declinedURL' =>  $declinedURL,
      'attendingURL' => $attendingURL
    ]);
  }
}
