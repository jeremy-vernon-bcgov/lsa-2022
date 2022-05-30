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
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DateTimeZone;

class   RecipientCeremonyInvitationConfirm extends Mailable
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
  * @var \App\Models\Attendee;
  */
  public $attendee;

  /**
  * Status
  * @var string;
  */
  public $status;

  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct(Recipient $recipient, Attendee $attendee)
  {
    $this->recipient = $recipient;
    $this->ceremony = Ceremony::with('locationAddress')
    ->where('id', '=', $attendee->ceremonies_id)->firstOrFail();
    $this->status = $attendee->status;
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {

    // format scheduled ceremony date/time
    $scheduled_datetime = new DateTime($this->ceremony->scheduled_datetime, new DateTimeZone('America/Vancouver'));

    // explode the ceremony location
    $ceremony = $this->ceremony->toArray();
    $location_address = $ceremony['location_address'];
    $location_name = !empty($location_address) ? $this->ceremony->location_name : 'TBD';
    $street_address = !empty($location_address) ? $location_address['street_address'] : '';
    $community = !empty($location_address) ? $location_address['community'] : '';
    $province = !empty($location_address) ? 'British Columbia' : '';

    if ($this->status === 'attending') {
      // gather confirmation data
      $certificate = [
        'first_name' => $this->recipient->first_name,
        'last_name' => $this->recipient->last_name,
        'location_name' => $location_name,
        'street_address' => $street_address,
        'community' => $community,
        'province' => $province,
        'scheduled_date' => $scheduled_datetime->format('l jS F Y'),
        'scheduled_time' => $scheduled_datetime->format('g:ia'),
      ];

      // generate PDF confirmation to attach to email
      $pdf = PDF::loadView('documents.rsvpCertificate', $certificate);

      // generate confirmation email with confirmation attachment
      $this
      ->subject('Confirmation to Attend Long Service Awards Ceremony')
      ->attachData($pdf->output(), 'lsa-confirmation.pdf')
      ->view('emails.recipientCeremonyRsvpAccept', [
        'first_name' => $this->recipient->first_name,
        'last_name' => $this->recipient->last_name,
        'scheduled_date' => $scheduled_datetime->format('l jS F Y'),
        'scheduled_time' => $scheduled_datetime->format('g:ia'),
      ]);
    }
    if ($this->status === 'declined') {
      // generate confirmation email
      $this
      ->subject('Confirmation to Not Attend Long Service Awards Ceremony')
      ->view('emails.recipientCeremonyRsvpDecline', [
        'first_name' => $this->recipient->first_name,
        'last_name' => $this->recipient->last_name,
      ]);
    }
  }
}
