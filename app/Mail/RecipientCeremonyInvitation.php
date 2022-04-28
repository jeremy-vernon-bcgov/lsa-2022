<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Recipient;
use App\Models\Ceremony;

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
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct(Recipient $recipient, Ceremony $ceremony, string $key, string $token)
  {
    $this->recipient = $recipient;
    $this->ceremony = $ceremony;
    $this->key = $key;
    $this->token = $token;
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    $baseURL = env('FRONTEND_URL') . '/admin/rsvp';
    $query = http_build_query(array(
      'key' => $this->key,
      'token' => $this->token
      ));
    $declinedURL = "$baseURL/declined/?$query";
    $attendingURL = "$baseURL/attending/?$query";
    $scheduled_datetime = date_format(date_create($this->ceremony->scheduled_datetime), 'g:ia \o\n l jS F Y');

    Log::info('RSVP', array(
      'first_name' => $this->recipient->first_name,
      'last_name' => $this->recipient->last_name,
      'scheduled_datetime' => $this->ceremony->scheduled_datetime,
      'declinedURL' =>  $declinedURL,
      'attendingURL' => $attendingURL
    ));

    return $this
    ->subject('Invitation to Long Service Awards Ceremony')
    ->view('emails.RecipientCeremonyInvitation', [
      'first_name' => $this->recipient->first_name,
      'last_name' => $this->recipient->last_name,
      'scheduled_datetime' => $scheduled_datetime,
      'declinedURL' =>  $declinedURL,
      'attendingURL' => $attendingURL
    ]);
  }
}
