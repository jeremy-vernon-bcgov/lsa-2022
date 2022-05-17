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
  public function __construct(Recipient $recipient, Attendee $attendee)
  {
    $this->recipient = $recipient;
    $this->ceremony = Ceremony::where('id', '=', $attendee->ceremonies_id)->firstOrFail();
    $this->attendee = $attendee;
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    // get attendee data
    $id = $this->attendee->id;
    $status = $this->attendee->status;

    // format scheduled ceremony date/time
    $scheduled_datetime = new DateTime($this->ceremony->scheduled_datetime);
    $scheduled_datetime->setTimezone(new DateTimeZone('America/Vancouver'));

    if ($status === 'attending') {

      // gather confirmation data
      $confirmation = [
        'first_name' => $this->recipient->first_name,
        'last_name' => $this->recipient->last_name,
        'location' => '',
        'scheduled_datetime' => $scheduled_datetime->format('g:ia T \o\n l jS F Y'),
      ];

      // generate PDF confirmation to attach to email
      $pdf = PDF::loadView('documents.rsvpConfirmation', $confirmation);

      // generate confirmation email with confirmation attachment
      $this
      ->subject('Confirmation to Attend Long Service Awards Ceremony')
      ->attachData($pdf->output(), 'lsa-confirmation.pdf')
      ->view('emails.recipientCeremonyRsvpAccept', [
        'first_name' => $this->recipient->first_name,
        'last_name' => $this->recipient->last_name,
        'scheduled_datetime' => $scheduled_datetime->format('g:ia T \o\n l jS F Y'),
      ]);
    }
    if ($status === 'declined') {
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
