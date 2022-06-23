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
    $this->attendee = $attendee;
    $this->ceremony = $ceremony;
    $this->token = $token;
    $this->expiry = $expiry->format('g:ia \o\n l, F j Y');
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

    // format scheduled ceremony date/time
    $scheduled_datetime = new DateTime($this->ceremony->scheduled_datetime, new DateTimeZone('America/Vancouver'));

    // explode the ceremony location
    $ceremony = $this->ceremony->toArray();
    $location_address = $ceremony['location_address'];
    $location_name = !empty($location_address) ? $this->ceremony->location_name : 'TBD';
    $street_address = !empty($location_address) ? $location_address['street_address'] : '';
    $community = !empty($location_address) ? $location_address['community'] : '';
    $province = !empty($location_address) ? 'British Columbia' : '';

    // send attendee ID as RSVP key
    $id = $this->attendee->id;
    $token = $this->token;
    $declinedURL = "$baseURL/declined/$id/$token";
    $attendingURL = "$baseURL/attending/$id/$token";

    return $this
    ->subject('Invitation to the Long Service Awards Ceremony')
    ->view('emails.recipientCeremonyInvitation', [
      'first_name' => $this->recipient->first_name,
      'last_name' => $this->recipient->last_name,
      'scheduled_date' => $scheduled_datetime->format('l, F j Y'),
      'scheduled_time' => $scheduled_datetime->format('g:ia'),
      'location_name' => $location_name,
      'street_address' => $street_address,
      'community' => $community,
      'province' => $province,
      'expiry' => $this->expiry,
      'declinedURL' =>  $declinedURL,
      'attendingURL' => $attendingURL
    ]);
  }
}
